#include <iostream>
#include <cassert>
#include <glog/logging.h>

#include "Runner.h"
#include "Config.h"

using namespace std;


int main(int argc, const char *argv[]) {
	Runner runner;
	RunResult result = runner.compile();
	std::cout << result.get_print_string() << std::endl;
	if (result != RunResult::COMPILE_ERROR) {
		result = runner.run();
		std::cout << result.get_print_string() << std::endl;
	}
	return 0;
}