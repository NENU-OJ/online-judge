#include <iostream>
#include <sys/resource.h>
#include <glog/logging.h>
#include <sys/time.h>
#include <csignal>
#include <queue>

#include "Runner.h"
#include "Config.h"
#include "Utils.h"
#include "Summit.h"

using namespace std;

void test_runner() {
	vector<string> src_list = {"tests/test_cpp.cpp", "tests/test_cpp11.cpp",
	                           "tests/test_java.java", "tests/test_py2.py", "tests/test_py3.py"};
	vector<int> lang_list = {Config::CPP_LANG, Config::CPP11_LANG, Config::JAVA_LANG, Config::PY2_LANG, Config::PY3_LANG};
	vector<string> input_list = { "tests/input", "", "", "", "tests/input"};
	for (int i = 0; i < src_list.size(); ++i) {
		std::string src = Utils::get_content_from_file(src_list[i]);
		Runner run(20000, 512 * 1024, lang_list[i], src);
		RunResult result = run.compile();
		cout << result.get_print_string() << endl;
		if (result != RunResult::COMPILE_ERROR) {
			result = run.run(input_list[i]);
			cout << result.get_print_string() << endl;
			//cout << Utils::get_content_from_file("temp_path/output") << endl;
		}
	}
}

queue<int> judge_queue;

static pthread_mutex_t queue_mtx = PTHREAD_MUTEX_INITIALIZER;

/**
 * get unfinished runs from database
 */
void init_queue() {
	// Mock
	judge_queue.push(1000);
	judge_queue.push(1001);
	judge_queue.push(1002);
}

/**
 * init socket listener
 */
void init_socket() {
	// Mock
}

/**
 * get run from socket
 * @return
 */
int next_runid() {
	// Mock
	sleep(2);
	return rand() % 2000;
}

void * listen_thread(void *arg) {

	while (true) {
		int runid = next_runid();
		pthread_mutex_lock(&queue_mtx);
		judge_queue.push(runid);
		LOG(INFO) << "Pushed " << runid << ".";
		pthread_mutex_unlock(&queue_mtx);
	}
}
void * judge_thread(void *arg) {

	while (true) {
		int runid;
		bool have_run = false;
		pthread_mutex_lock(&queue_mtx);
		if (!judge_queue.empty()) {
			have_run = true;
			runid = judge_queue.front();
			judge_queue.pop();
		}
		pthread_mutex_unlock(&queue_mtx);

		if (have_run) {
			LOG(INFO) << runid << " is Running.";
			Summit summit(runid);
			summit.work();
		}
	}
}

void init_threads() {

	/// listen thread
	pthread_t tid_listen;
	if (pthread_create(&tid_listen, NULL, listen_thread, NULL) != 0)
		LOG(FATAL) << "Can't init listen thread!";
	if (pthread_detach(tid_listen) != 0)
		LOG(FATAL) << "Can't detach listen thread!";

	/// judge_thread
	pthread_t tid_judge;
	if (pthread_create(&tid_judge, NULL, judge_thread, NULL) != 0)
		LOG(FATAL) << "Can't init judge thread!";
	if (pthread_detach(tid_judge) != 0)
		LOG(FATAL) << "Can't detach judge thread!";
}


int main(int argc, const char *argv[]) {

	test_runner();

	init_queue();
	init_socket();
	init_threads();

	while(true)
		sleep(3600);
	return 0;
}