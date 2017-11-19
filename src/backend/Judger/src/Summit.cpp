//
// Created by torapture on 17-11-16.
//

#include <algorithm>
#include <glog/logging.h>

#include "Summit.h"
#include "Runner.h"
#include "Config.h"
#include "Utils.h"

Summit::Summit() {

}


void Summit::set_runid(int runid) {
	this->runid = runid;
}

void Summit::set_pid(int pid) {
	this->pid = pid;
}

void Summit::set_time_limit_ms(int time_limit_ms) {
	this->time_limit_ms = time_limit_ms;
}

void Summit::set_memory_limit_kb(int memory_limit_kb) {
	this->memory_limit_kb = memory_limit_kb;
}

void Summit::set_language(int language) {
	this->language = language;
}

void Summit::set_is_spj(int is_spj) {
	this->is_spj = is_spj;
}

void Summit::set_std_input_file(const std::string &std_input_file) {
	this->std_input_file = std_input_file;
}

void Summit::set_std_output_file(const std::string &std_output_file) {
	this->std_output_file = std_output_file;
}

void Summit::set_user_output_file(const std::string &user_output_file) {
	this->user_output_file = user_output_file;
}

void Summit::set_src(const std::string &src) {
	this->src = src;
}

void Summit::work() {
	RunResult result;

	/// must have input and output file even if they are empty
	if (!Utils::check_file(std_input_file) || !Utils::check_file(std_output_file)) {
		result = RunResult::JUDGE_ERROR;
		LOG(INFO) << result.get_print_string();
		return;
	}

	Runner run(time_limit_ms, memory_limit_kb, language, src);
	result = run.compile();
	if (result != RunResult::COMPILE_ERROR) {
		result = run.run(std_input_file);
		if (result == RunResult::RUN_SUCCESS) {
			if (is_spj) {
				result = spj_check().set_time_used(result.time_used_ms).set_memory_used(result.memory_used_kb);
			} else {
				result = normal_check().set_time_used(result.time_used_ms).set_memory_used(result.memory_used_kb);
			}
		} else {
			// unsuccessful run
		}
	} else {
		// compile error
	}
	LOG(INFO) << result.get_print_string();
}

RunResult Summit::spj_check() {
	std::string spj_src_file = Config::get_instance()->get_spj_files_path() + std::to_string(pid) +
			"/spj" + Config::get_instance()->get_src_extention(is_spj);

	if (!Utils::check_file(spj_src_file))
		return RunResult::JUDGE_ERROR;

	std::string spj_src = Utils::get_content_from_file(spj_src_file);


	Runner spj(Config::get_instance()->get_spj_run_time_ms(), Config::get_instance()->get_spj_memory_kb(),
			   is_spj, spj_src);

	std::string user_output = Config::get_instance()->get_temp_path() + "user_output";

	std::string cp_cmd = "cp " + user_output_file + " " + user_output;

	system(cp_cmd.c_str());

	std::string spj_info = std_input_file + "\n"
						 + std_output_file + "\n"
						 + user_output + "\n";

	std::string spj_info_file = Config::get_instance()->get_temp_path() + "spj_info";

	Utils::save_to_file(spj_info_file, spj_info);

	RunResult result = spj.compile();
	if (result == RunResult::COMPILE_ERROR)
		return RunResult::JUDGE_ERROR;

	result = spj.run(spj_info_file);

	if (result == RunResult::RUN_SUCCESS)
		return RunResult::ACCEPTED;
	else
		return RunResult::WRONG_ANSWER;
}

RunResult Summit::normal_check() {
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