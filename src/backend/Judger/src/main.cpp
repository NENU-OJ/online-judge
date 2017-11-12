#include <iostream>
#include <cassert>

#include "Runner.h"
#include "Config.h"

using namespace std;


int main(int argc, const char *argv[]) {
//	Runner runner;
//	RunResult result = runner.run();

//	cout << result.get_print_string() << endl;


	Config::get_instance();
	cout << Config::get_instance()->get_print_string() << endl;
	cout << Config::get_instance()->get_compile_time_ms() << endl;

//	result = RunResult::ACCEPTED.set_time_used(12).set_memory_used(22).set_ce_info("fucking ce info").set_memory_used(64);
//	cout << result.get_print_string() << endl;
//
//	result = RunResult::COMPILE_ERROR.set_time_used(12).set_memory_used(22).set_ce_info("fucking ce info").set_memory_used(64);
//	cout << result.get_print_string() << endl;


	assert(RunResult::COMPILE_ERROR == RunResult::COMPILE_ERROR);
	assert(RunResult::ACCEPTED == RunResult::ACCEPTED);
	//assert(RunResult::ACCEPTED == RunResult::WRONG_ANSWER);

	return 0;
}