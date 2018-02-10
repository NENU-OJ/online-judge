#include <iostream>
#include <glog/logging.h>
#include <queue>
#include <sys/socket.h>
#include <netinet/in.h>
#include <wait.h>

#include "Runner.h"
#include "Config.h"
#include "Utils.h"
#include "Submit.h"

#include "DatabaseHandler.h"
#include "Exception.h"

using namespace std;

static queue<Submit *> judge_queue;
static int main_sockfd;
static pthread_mutex_t queue_mtx = PTHREAD_MUTEX_INITIALIZER;

/**
 * get unfinished runs from database
 */

void init_queue() {
	LOG(INFO) << "init queue";
	DatabaseHandler db;
	auto unfinished_runs = db.get_unfinished_results();
	for (auto &run : unfinished_runs) {
		int runid = atoi(run["id"].c_str());
		int pid = atoi(run["problem_id"].c_str());
		int uid = atoi(run["user_id"].c_str());
		int contest_id = atoi(run["contest_id"].c_str());
		try {
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

			judge_queue.push(submit);
			db.change_run_result(runid, RunResult::QUEUEING);
			LOG(INFO) << "init enqueue runid: " << runid;
		} catch (Exception &e) {
			LOG(ERROR) << e.what();
		}
	}
	LOG(INFO) << "init queue finished";
}

/**
 * init socket listener
 */
void init_socket() {

	LOG(INFO) << "init socket";

	if ((main_sockfd = socket(AF_INET, SOCK_STREAM, 0)) == -1) {
		LOG(FATAL) << "socket error";
	}

	sockaddr_in my_addr;
	my_addr.sin_family = AF_INET;
	my_addr.sin_port = htons(Config::get_instance()->get_listen_port());
	my_addr.sin_addr.s_addr = INADDR_ANY;
	bzero(&(my_addr.sin_zero), 8);

	LOG(INFO) << "bind socket";

	if (bind(main_sockfd, (struct sockaddr *) & my_addr,
	         sizeof (struct sockaddr)) == -1) {
		LOG(FATAL) << "bind error";
	}

	LOG(INFO) << "start listen";

	if (listen(main_sockfd, 5) == -1) {
		LOG(FATAL) << "listen error";
	}

	LOG(INFO) << "init socket finished";
}

/**
 * get run from socket
 * @return
 */
int next_runid() { // TODO fuck with exception
	int cfd = accept(main_sockfd, NULL, NULL);

	LOG(INFO) << "accepted connection fd: " << cfd;

	static char buf[128];
	int num_read = 0;
	int tries = 5;
	while (num_read == 0 && tries--) {
		usleep(1280);
		num_read += read(cfd, buf, sizeof(buf));
	}
	buf[num_read] = '\0';
	close(cfd);
	int runid = atoi(buf);

	return runid;
}

void * listen_thread(void *arg) {

	while (true) {
		try {
			int runid = next_runid();
			Submit *submit = Submit::get_from_runid(runid);
			DatabaseHandler db;
			db.change_run_result(runid, RunResult::QUEUEING);
			pthread_mutex_lock(&queue_mtx);
			judge_queue.push(submit);
			LOG(INFO) << "[listen thread] socket enqueue runid: " << runid;
			pthread_mutex_unlock(&queue_mtx);
		} catch (Exception &e) {
			LOG(ERROR) << e.what();
		}
	}
}
void * judge_thread(void *arg) {

	while (true) {
		Submit *submit;
		bool have_run = false;
		pthread_mutex_lock(&queue_mtx);
		if (!judge_queue.empty()) {
			have_run = true;
			submit = judge_queue.front();
			judge_queue.pop();
		}
		pthread_mutex_unlock(&queue_mtx);

		if (have_run) {
			try {
				LOG(INFO) << "[judge thread] send runid: " << submit->get_runid() << " to work";
				submit->work();
				delete submit;
				submit = nullptr;
			} catch (Exception &e) {
				LOG(ERROR) << e.what();
				if (submit != nullptr)
					delete submit;
			}
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

	LOG(INFO) << "listen thread init finished";

	/// judge_thread
	pthread_t tid_judge;
	if (pthread_create(&tid_judge, NULL, judge_thread, NULL) != 0)
		LOG(FATAL) << "Can't init judge thread!";
	if (pthread_detach(tid_judge) != 0)
		LOG(FATAL) << "Can't detach judge thread!";

	LOG(INFO) << "judge thread init finished";
}

void test_runs() {
	vector<string> src_list = {"tests/test_cpp.cpp", "tests/test_cpp11.cpp", "tests/test_java.java", "tests/test_py2.py", "tests/test_py3.py"};
	vector<int> lang_list = {Config::CPP_LANG, Config::CPP11_LANG, Config::JAVA_LANG, Config::PY2_LANG, Config::PY3_LANG};
	for (int i = 0; i < src_list.size(); ++i) {
		Runner runner(1000, 32768, lang_list[i], Utils::get_content_from_file(src_list[i]));
		RunResult result = runner.compile();
		if (result != RunResult::COMPILE_ERROR)
			cout << runner.run("tests/input").get_print_string() << endl;
		else
			cout << result.get_print_string() << endl;
	}
}


void test_set_uid() {
	pid_t pid;
	if ((pid = fork()) == 0) {
		if (setuid(Config::get_instance()->get_low_privilege_uid()) == -1)
			exit(1);
		else
			exit(0);
	} else {
		int status;
		waitpid(pid, &status, 0);
		if (WIFEXITED(status)) {
			if (WEXITSTATUS(status) == 0)
				LOG(INFO) << "can set low_privilege_uid" << endl;
			else if (WEXITSTATUS(status) == 1)
				LOG(FATAL) << "can't set low_privilege_uid";
			else
				LOG(FATAL) << "unknown error";
		} else {
			LOG(FATAL) << "unknown error";
		}
	}
}



int main(int argc, const char *argv[]) {

	test_set_uid();
	init_socket();
	init_queue();
	init_threads();

	while(true)
		sleep(3600);
	return 0;
}