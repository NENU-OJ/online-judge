//
// Created by torapture on 17-11-16.
//

#include <algorithm>
#include <glog/logging.h>

#include "Submit.h"
#include "Runner.h"
#include "Config.h"
#include "Utils.h"
#include "DatabaseHandler.h"

Submit::Submit() {

}


void Submit::set_runid(int runid) {
	this->runid = runid;
}

void Submit::set_uid(int uid) {
	this->uid = uid;
}

void Submit::set_pid(int pid) {
	this->pid = pid;
}

void Submit::set_contest_id(int contest_id) {
	this->contest_id = contest_id;
}

void Submit::set_time_limit_ms(int time_limit_ms) {
	this->time_limit_ms = time_limit_ms;
}

void Submit::set_memory_limit_kb(int memory_limit_kb) {
	this->memory_limit_kb = memory_limit_kb;
}

void Submit::set_language(int language) {
	this->language = language;
}

void Submit::set_is_spj(int is_spj) {
	this->is_spj = is_spj;
}

void Submit::set_std_input_file(const std::string &std_input_file) {
	this->std_input_file = std_input_file;
}

void Submit::set_std_output_file(const std::string &std_output_file) {
	this->std_output_file = std_output_file;
}

void Submit::set_user_output_file(const std::string &user_output_file) {
	this->user_output_file = user_output_file;
}

void Submit::set_src(const std::string &src) {
	this->src = src;
}

void Submit::work() {
	RunResult result;
	DatabaseHandler db;

	/// must have input and output file even if they are empty
	if (!Utils::check_file(std_input_file) || !Utils::check_file(std_output_file)) {
		result = RunResult::JUDGE_ERROR;
		db.change_run_result(runid, result);
		LOG(ERROR) << "problem: " << pid << " does not have input_file or output_file supposed in " << std_input_file << " and " << std_output_file;
		LOG(INFO) << "result runid: " << runid << " " << result.status;
		return;
	}

	if (language != Config::CPP_LANG && language != Config::CPP11_LANG) {
		time_limit_ms *= Config::get_instance()->get_vm_multiplier();
		memory_limit_kb *= Config::get_instance()->get_vm_multiplier();
	}

	Runner run(time_limit_ms, memory_limit_kb, language, src);

	LOG(INFO) << "compiling runid: " << runid;
	db.change_run_result(runid, RunResult::COMPILING);
	result = run.compile();

	if (result != RunResult::COMPILE_ERROR) {
		LOG(INFO) << "running runid: " << runid;
		db.change_run_result(runid, RunResult::RUNNING);
		result = run.run(std_input_file);
		if (result == RunResult::RUN_SUCCESS) {
			if (is_spj) {
				result = spj_check().set_time_used(result.time_used_ms).set_memory_used(result.memory_used_kb);
			} else {
				result = normal_check().set_time_used(result.time_used_ms).set_memory_used(result.memory_used_kb);
			}
		}
	}
	std::string output_file = Config::get_instance()->get_temp_path() + Config::get_instance()->get_output_file();
	if (Utils::check_file(output_file)) Utils::delete_file(output_file);

	LOG(INFO) << "result runid: " << runid << " " << result.status;

	if (result == RunResult::ACCEPTED) {
		if (contest_id == 0) {
			if (!db.already_accepted(uid, pid)) {
				db.add_user_total_solved(uid);
			}
			db.add_user_total_accepted(uid);
		} else {
			db.add_contest_total_accepted(contest_id, pid);
		}
	}

	db.change_run_result(runid, result);

	if (contest_id == 0)
		db.add_problem_result(pid, result);

}

RunResult Submit::spj_check() {

	LOG(INFO) << "spj check runid: " << runid;

	std::string spj_src_file = Config::get_instance()->get_spj_files_path() + std::to_string(pid) +
			"/spj" + Config::get_instance()->get_src_extention(is_spj);

	if (!Utils::check_file(spj_src_file)) {
		LOG(ERROR) << "spj for problem: " << pid << " need " << spj_src_file;
		return RunResult::JUDGE_ERROR;
	}

	std::string spj_src = Utils::get_content_from_file(spj_src_file);


	Runner spj(Config::get_instance()->get_spj_run_time_ms(), Config::get_instance()->get_spj_memory_kb(),
			   is_spj, spj_src);

	std::string spj_user_output = Config::get_instance()->get_temp_path() + "spj_user_output";

	std::string cp_cmd = "cp " + user_output_file + " " + spj_user_output;

	system(cp_cmd.c_str());

	std::string spj_info = std_input_file + "\n"
						 + std_output_file + "\n"
						 + spj_user_output + "\n";

	std::string spj_info_file = Config::get_instance()->get_temp_path() + "spj_info";

	Utils::save_to_file(spj_info_file, spj_info);

	LOG(INFO) << "spj compiling runid: " << runid;

	RunResult result = spj.compile();

	if (result == RunResult::COMPILE_ERROR) {
		LOG(ERROR) << "spj program for problem: " << pid << " in " << spj_src_file << " have Compile Error";
		return RunResult::JUDGE_ERROR;
	}

	LOG(INFO) << "spj running runid: " << runid;
	result = spj.run(spj_info_file);

	LOG(INFO) << "spj result runid: " << runid << " " << result.status;

	if (Utils::check_file(spj_info_file)) Utils::delete_file(spj_info_file);
	if (Utils::check_file(spj_user_output)) Utils::delete_file(spj_user_output);

	if (result == RunResult::RUN_SUCCESS)
		return RunResult::ACCEPTED;
	else
		return RunResult::WRONG_ANSWER;
}

RunResult Submit::normal_check() {

	LOG(INFO) << "normal check runid: " << runid;

	bool aced = true, peed = true;
	FILE *program_out, *standard_out;
	int eofp = EOF, eofs = EOF;
	program_out = fopen(user_output_file.c_str(), "r");
	standard_out = fopen(std_output_file.c_str(), "r");

	char po_char, so_char;
	while (1) {
		while ((eofs = fscanf(standard_out, "%c", &so_char)) != EOF &&
		       so_char == '\r');
		while ((eofp = fscanf(program_out, "%c", &po_char)) != EOF &&
		       po_char == '\r');
		if (eofs == EOF || eofp == EOF) break;
		if (so_char != po_char) {
			aced = false;
			break;
		}
	}
	while ((so_char == '\n' || so_char == '\r') &&
	       ((eofs = fscanf(standard_out, "%c", &so_char)) != EOF));
	while ((po_char == '\n' || po_char == '\r') &&
	       ((eofp = fscanf(program_out, "%c", &po_char)) != EOF));
	if (eofp != eofs) aced = false;
	fclose(program_out);
	fclose(standard_out);
	if (!aced) {
		program_out = fopen(user_output_file.c_str(), "r");
		standard_out = fopen(std_output_file.c_str(), "r");
		while (1) {
			while ((eofs = fscanf(standard_out, "%c", &so_char)) != EOF &&
			       (so_char == ' ' || so_char == '\n' || so_char == '\r'));
			while ((eofp = fscanf(program_out, "%c", &po_char)) != EOF &&
			       (po_char == ' ' || po_char == '\n' || po_char == '\r'));
			if (eofs == EOF || eofp == EOF) break;
			if (so_char != po_char) {
				peed = false;
				break;
			}
		}
		while ((so_char == ' ' || so_char == '\n' || so_char == '\r') &&
		       ((eofs = fscanf(standard_out, "%c", &so_char)) != EOF));
		while ((po_char == ' ' || po_char == '\n' || po_char == '\r') &&
		       ((eofp = fscanf(program_out, "%c", &po_char)) != EOF));
		if (eofp != eofs) {
			peed = false;
		}
		fclose(program_out);
		fclose(standard_out);
	}
	if (aced) return RunResult::ACCEPTED;
	else if (peed) return RunResult::PRESENTATION_ERROR;
	else return RunResult::WRONG_ANSWER;
}

Submit * Submit::get_from_runid(int runid) {
	DatabaseHandler db;
	auto run = db.get_run_stat(runid);
	int pid = atoi(run["problem_id"].c_str());
	int uid = atoi(run["user_id"].c_str());
	int contest_id = atoi(run["contest_id"].c_str());
	auto problem_info = db.get_problem_description(pid);
	Submit *submit = new Submit();
	submit->set_runid(runid);
	submit->set_pid(pid);
	submit->set_uid(uid);
	submit->set_contest_id(contest_id);
	submit->set_time_limit_ms(atoi(problem_info["time_limit"].c_str()));
	submit->set_memory_limit_kb(atoi(problem_info["memory_limit"].c_str()));
	submit->set_language(atoi(run["language_id"].c_str()));
	submit->set_is_spj(atoi(problem_info["is_special_judge"].c_str()));
	submit->set_std_input_file(Utils::get_input_file(pid));
	submit->set_std_output_file(Utils::get_output_file(pid));
	submit->set_user_output_file(Utils::get_user_output_file());
	submit->set_src(run["source"]);
	return submit;
}
