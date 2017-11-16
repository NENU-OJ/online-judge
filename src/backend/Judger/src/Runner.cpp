//
// Created by torapture on 17-11-12.
//

#include <sys/time.h>
#include <sys/resource.h>
#include <wait.h>
#include <glog/logging.h>

#include "Runner.h"
#include "Config.h"
#include "Utils.h"


Runner::Runner() {
	this->time_limit_ms = DEFAULT_TIME_LIMIT_MS;
	this->memory_limit_kb = DEFAULT_MEMORY_LIMIT_KB;
	this->stack_limit_kb = DEFAULT_STACK_LIMIT_KB;
	this->output_limit_kb = DEFAULT_OUTPUT_LIMIT_KB;

	this->language = Config::CPP_LANG;
	this->src = "main(){}";

	this->input_file = "";
	this->src_file_name = Config::get_instance()->get_temp_path() + Config::get_instance()->get_source_file() + Config::get_instance()->get_src_extention(language);
	this->exc_file_name = Config::get_instance()->get_temp_path() + Config::get_instance()->get_binary_file() + Config::get_instance()->get_exc_extentino(language);
}

Runner::Runner(int time_limit_ms, int memory_limit_kb,
       int stack_limit_kb, int output_limit_kb,
       int language, const std::string &src) {

	this->time_limit_ms = time_limit_ms;
	this->memory_limit_kb = memory_limit_kb;
	this->stack_limit_kb = stack_limit_kb;
	this->output_limit_kb = output_limit_kb;

	this->language = language;
	this->src = src;

	this->input_file = "";
	this->src_file_name = Config::get_instance()->get_temp_path() + Config::get_instance()->get_source_file() + Config::get_instance()->get_src_extention(language);
	this->exc_file_name = Config::get_instance()->get_temp_path() + Config::get_instance()->get_binary_file() + Config::get_instance()->get_exc_extentino(language);
}

void Runner::child_compile() {

	/// redirect stderr stream
	std::string ce_info_file = Config::get_instance()->get_temp_path() + Config::get_instance()->get_ce_info_file();
	freopen(ce_info_file.c_str(), "w", stderr);

	// TODO set uid

	/// set time limit TODO CPU time or user time
	itimerval itv;
	itv.it_value.tv_sec = Config::get_instance()->get_compile_time_ms() / 1000;
	itv.it_value.tv_usec = Config::get_instance()->get_compile_time_ms() % 1000 * 1000;
	itv.it_interval.tv_sec = 0;
	itv.it_interval.tv_usec = 0;
	setitimer(ITIMER_REAL, &itv, NULL);

	/// compile
	if (Config::CPP_LANG == language) {
		execl("/usr/bin/g++", "g++", src_file_name.c_str(), "-o",
		      exc_file_name.c_str(), "-O2", "-fno-asm", "-Wall", "-lm", NULL);
	} else if (Config::CPP11_LANG == language) {
		execl("/usr/bin/g++", "g++", "-std=c++11", src_file_name.c_str(), "-o",
		      exc_file_name.c_str(), "-O2", "-fno-asm", "-Wall", "-lm", NULL);
	} else if (Config::JAVA_LANG == language) {
		execl("/usr/bin/javac", "javac", "-g:none", "-Xlint",
		      src_file_name.c_str(), NULL);
	} else if (Config::PY2_LANG == language) {
		execl("/usr/bin/python2", "python2", "-c",
		      ((std::string)"import py_compile; py_compile.compile('" +
						src_file_name + "')").c_str(),
				NULL);
	} else if (Config::PY3_LANG == language) {
		execl("/usr/bin/python3", "python3", "-c",
				((std::string) "import py_compile; py_compile.compile('" +
						src_file_name + "', cfile='" + exc_file_name + "')").c_str(),
				NULL);
	}
	/// Not suppose to get here if compile success
	fputs("Fucking Error.", stderr);
	exit(1);
}

RunResult Runner::compile() {
	pid_t cid;

	/// write to src file
	if (!Utils::save_to_file(src_file_name, src)) {
		return RunResult::COMPILE_ERROR.set_ce_info("Can not save to file.");
	}

	/// try to compile

	if ((cid = fork()) == -1) {
		return RunResult::JUDGE_ERROR;
	} else if (cid == 0) { // child process
		child_compile();
		exit(0); /// Not suppose to get here
	} else { // father process
		int status;
		rusage run_info;

		if (wait4(cid, &status, WUNTRACED, &run_info) == -1)
			return RunResult::JUDGE_ERROR;

		int time_used_ms = get_time_ms(run_info);
		int memory_used_kb = get_memory_kb(run_info);

		std::string ce_info_file = Config::get_instance()->get_temp_path() + Config::get_instance()->get_ce_info_file();
		std::string ce_info = Utils::get_content_from_file(ce_info_file);

		if (ce_info.empty() && WIFEXITED(status) && WEXITSTATUS(status) == 0) {
			return RunResult::COMPILE_SUCCESS.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
		} else {
			if (WIFSIGNALED(status) && WTERMSIG(status) == SIGALRM) { // compile time limit exceeded
				time_used_ms = Config::get_instance()->get_compile_time_ms();
				ce_info = "Compile time limit exceeded.";
			}
			return RunResult::COMPILE_ERROR.set_time_used(time_used_ms).set_memory_used(memory_used_kb).set_ce_info(ce_info);
		}
	}
}

void Runner::child_run() { // TODO set limit and run

	/// redirect stdin stream
	if (!input_file.empty())
		freopen(input_file.c_str(), "r", stdin);

	/// redirect stdout stream
	std::string output_file = Config::get_instance()->get_temp_path() + Config::get_instance()->get_output_file();
	freopen(output_file.c_str(), "w", stdout);

	/// set time limit
	itimerval itv;
	itv.it_value.tv_sec = time_limit_ms / 1000;
	itv.it_value.tv_usec = time_limit_ms % 1000 * 1000;
	itv.it_interval.tv_sec = 0;
	itv.it_interval.tv_usec = 0;
	setitimer(ITIMER_REAL, &itv, NULL);

	/// set memory limit
//	rlimit memory_limit;
//	memory_limit.rlim_max = memory_limit.rlim_cur = memory_limit_kb * 1024;
//	setrlimit(RLIMIT_AS, &memory_limit);

	/// set stack limit
	//rlimit stack_limit;


	/// set output limit
	rlimit output_limit;
	output_limit.rlim_max = output_limit.rlim_cur = Config::get_instance()->get_max_output_limit() * 1024 * 1024;
	setrlimit(RLIMIT_FSIZE, &output_limit);

	// TODO set uid
	/// set security limit

	/// run
	if (Config::CPP_LANG == language || Config::CPP11_LANG == language) {
		execl(exc_file_name.c_str(), exc_file_name.c_str(), NULL);
	} else if (Config::JAVA_LANG == language) {
		chdir(Config::get_instance()->get_temp_path().c_str());
		execl("/usr/bin/java", "java", "-Djava.security.manager",
		      "-Djava.security.policy=java.policy", "-client",
		      Config::get_instance()->get_binary_file().c_str(), NULL);
	} else if (Config::PY2_LANG == language) {
		execl("/usr/bin/python2", "python2", exc_file_name.c_str(), NULL);
	} else if (Config::PY3_LANG == language) {
		execl("/usr/bin/python3", "python3", exc_file_name.c_str(), NULL);
	}
}

RunResult Runner::run(const std::string &input_file) { // suppose compile success
	this->input_file = input_file;
	pid_t cid = fork();

	if (cid == -1) {
		return RunResult::JUDGE_ERROR;
	} else if (cid == 0) { // child process
		child_run();
		exit(0); /// do not return
	} else { // father process
		int status;
		rusage run_info;

		if (wait4(cid, &status, WUNTRACED, &run_info) == -1)
			return RunResult::JUDGE_ERROR;

		int time_used_ms = get_time_ms(run_info);
		int memory_used_kb = get_memory_kb(run_info);

		if (time_used_ms > time_limit_ms)
			return RunResult::TIME_LIMIT_EXCEEDED.set_time_used(time_used_ms).set_memory_used(memory_used_kb);

		if (WIFSIGNALED(status) && WTERMSIG(status) != SIGTRAP) {
			if (WTERMSIG(status) == SIGALRM)
				return RunResult::TIME_LIMIT_EXCEEDED.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
			else if (WTERMSIG(status) == SIGXFSZ)
				return RunResult::OUTPUT_LIMIT_EXCEEDED.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
			else
				return RunResult::RUNTIME_ERROR.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
		} else if (WIFSTOPPED(status) && WSTOPSIG(status) != SIGTRAP) {
			if (WSTOPSIG(status) == SIGALRM)
				return RunResult::TIME_LIMIT_EXCEEDED.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
			else if (WSTOPSIG(status) == SIGXFSZ)
				return RunResult::OUTPUT_LIMIT_EXCEEDED.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
			else
				return RunResult::RUNTIME_ERROR.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
		} else if (WIFEXITED(status)) {
			if (WEXITSTATUS(status) != 0) {
				return RunResult::RUNTIME_ERROR.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
			}
		}

		return RunResult::RUN_SUCCESS.set_time_used(time_used_ms).set_memory_used(memory_used_kb);
	}
}

RunResult Runner::run() {
	return run("");
}

int Runner::get_time_ms(const rusage &run_info) {
	return run_info.ru_utime.tv_sec * 1000 + run_info.ru_utime.tv_usec / 1000;
}

int Runner::get_memory_kb(const rusage &run_info) {
	return run_info.ru_minflt * (getpagesize() / 1024);
}
