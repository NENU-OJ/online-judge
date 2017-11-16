#include <iostream>
#include <sys/resource.h>
#include <glog/logging.h>

#include "Runner.h"
#include "Config.h"
#include "Utils.h"

using namespace std;


int main(int argc, const char *argv[]) {
	rusage run_info;

	vector<int> vec;

//	for(int i = 0; i <= 1000000; ++i) {
//		vec.push_back(i);
//
//		if (i % 1000 == 0) {
//			getrusage(RUSAGE_SELF, &run_info);
//			printf("mem_used = %dkb\n", Runner::get_memory_kb(run_info));
//		}
//
//	}

//	sleep(3);
//	getrusage(RUSAGE_SELF, &run_info);
//
//	printf("used %dms\n", run_info.ru_utime.tv_usec / 1000 + run_info.ru_utime.tv_sec);
//	printf("used %dms\n", run_info.ru_stime.tv_usec / 1000 + run_info.ru_stime.tv_sec);
//	printf("mem used %d\n", run_info.ru_maxrss);

	vector<string> src_list = {"tests/test_cpp.cpp", "tests/test_cpp11.cpp",
	                           "tests/test_java.java", "tests/test_py2.py", "tests/test_py3.py"};
	vector<int> lang_list = {Config::CPP_LANG, Config::CPP11_LANG, Config::JAVA_LANG, Config::PY2_LANG, Config::PY3_LANG};
	vector<string> input_list = { "tests/input", "", "", "", "tests/input"};
	for (int i = 0; i < src_list.size(); ++i) {
		std::string src = Utils::get_content_from_file(src_list[i]);
		Runner run(20000, 512 * 1024, 10000, 10000, lang_list[i], src);
		RunResult result = run.compile();
		cout << result.get_print_string() << endl;
		if (result != RunResult::COMPILE_ERROR) {
			result = run.run(input_list[i]);
			cout << result.get_print_string() << endl;
			//cout << Utils::get_content_from_file("temp_path/output") << endl;
		}
	}
	return 0;
}