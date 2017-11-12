//
// Created by torapture on 17-11-12.
//

#include <sys/time.h>
#include <sys/resource.h>
#include <wait.h>

#include "Runner.h"


Runner::Runner() {
	Runner(DEFAULT_CPP_TIME_LIMIT_MS, DEFAULT_OTHER_TIME_LIMIT_MS,
	       DEFAULT_CPP_MEMORY_LIMIT_KB, DEFAULT_OTHER_MEMORY_LIMIT_KB,
	       DEFAULT_STACK_LIMIT_KB, DEFAULT_OUTPUT_LIMIT_KB, CPP_LANG, "main(){}");
}
Runner::Runner(int cpp_time_limit_ms, int other_time_limit_ms,
               int cpp_memory_limit_kb, int other_memory_limit_kb,
               int stack_limit_kb, int output_limit_kb,
			   int language, const std::string &src) {

	this->cpp_time_limit_ms = cpp_time_limit_ms;
	this->other_time_limit_ms = other_time_limit_ms;

	this->cpp_memory_limit_kb = cpp_memory_limit_kb;
	this->other_memory_limit_kb = other_memory_limit_kb;

	this->stack_limit_kb = stack_limit_kb;
	this->output_limit_kb = output_limit_kb;

	this->language = language;
	this->src = src;
}

void Runner::child_compile() {  // TODO set limit and compile

}

void Runner::child_run() { // TODO set limit and run

}

RunResult Runner::compile() {

	pid_t pid = fork();

	if (pid == -1) {
		return RunResult::JUDGE_ERROR;
	} else if (pid == 0) { // child process
		child_compile();
		exit(0); /// do not return
	} else { // father process
		return RunResult::COMPILE_SUCCESS;
	}
}

RunResult Runner::run() {

	pid_t pid = fork();

	if (pid == -1) {
		return RunResult::JUDGE_ERROR;
	} else if (pid == 0) { // child process
		child_run();
		exit(0); /// do not return
	} else { // father process
		return RunResult::WRONG_ANSWER.set_time_used(64).set_memory_used(12);
	}
}