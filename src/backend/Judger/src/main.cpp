#include <iostream>
#include <glog/logging.h>
#include <queue>
#include <sys/socket.h>
#include <netinet/in.h>

#include "Runner.h"
#include "Config.h"
#include "Utils.h"
#include "Summit.h"

#include "DatabaseHandler.h"

using namespace std;

static queue<Summit> judge_queue;
static int main_sockfd;
static pthread_mutex_t queue_mtx = PTHREAD_MUTEX_INITIALIZER;

/**
 * get unfinished runs from database
 */

void init_queue() {
	// Mock
	DatabaseHandler db;
	auto unfinished_runs = db.get_unfinished_results();
	for (auto &run : unfinished_runs) {
		int runid = atoi(run["id"].c_str());
		int pid = atoi(run["problem_id"].c_str());
		int uid = atoi(run["user_id"].c_str());
		auto problem_info = db.get_problem_description(pid);
		Summit summit;
		summit.set_runid(runid);
		summit.set_pid(pid);
		summit.set_uid(uid);
		summit.set_time_limit_ms(atoi(problem_info["time_limit"].c_str()));
		summit.set_memory_limit_kb(atoi(problem_info["memory_limit"].c_str()));
		summit.set_language(atoi(run["language_id"].c_str()));
		summit.set_is_spj(atoi(problem_info["is_special_judge"].c_str()));
		summit.set_std_input_file(Utils::get_input_file(pid));
		summit.set_std_output_file(Utils::get_output_file(pid));
		summit.set_user_output_file(Utils::get_user_output_file());
		summit.set_src(run["source"]);

		judge_queue.push(summit);
		db.change_run_result(runid, RunResult::QUEUEING);
	}
}

/**
 * init socket listener
 */
void init_socket() {
	if ((main_sockfd = socket(AF_INET, SOCK_STREAM, 0)) == -1) {
		LOG(FATAL) << "socket() error";
	}

	sockaddr_in my_addr;
	my_addr.sin_family = AF_INET;
	my_addr.sin_port = htons(Config::get_instance()->get_listen_port());
	my_addr.sin_addr.s_addr = INADDR_ANY;
	bzero(&(my_addr.sin_zero), 8);

	if (bind(main_sockfd, (struct sockaddr *) & my_addr,
	         sizeof (struct sockaddr)) == -1) {
		LOG(FATAL) << "bind() error";
	}
	if (listen(main_sockfd, 5) == -1) {
		LOG(FATAL) << "listen() error";
	}
}

/**
 * get run from socket
 * @return
 */
int next_runid() {
	int cfd = accept(main_sockfd, NULL, NULL);
	static char buf[128];
	int num_read = 0;
	while (num_read == 0) {
		num_read += read(cfd, buf, sizeof(buf));
	}
	buf[num_read] = '\0';
	close(cfd);
	int runid = atoi(buf);
	return runid;
}

void * listen_thread(void *arg) {

	while (true) {
		int runid = next_runid();
		Summit summit = Summit::get_from_runid(runid);
		pthread_mutex_lock(&queue_mtx);
		judge_queue.push(summit);
		LOG(INFO) << "Pushed " << runid << ".";
		pthread_mutex_unlock(&queue_mtx);
		DatabaseHandler db;
		db.change_run_result(runid, RunResult::QUEUEING);
	}
}
void * judge_thread(void *arg) {

	while (true) {
		Summit summit;
		bool have_run = false;
		pthread_mutex_lock(&queue_mtx);
		if (!judge_queue.empty()) {
			have_run = true;
			summit = judge_queue.front();
			judge_queue.pop();
		}
		pthread_mutex_unlock(&queue_mtx);

		if (have_run) {
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

	init_queue();
	init_socket();
	init_threads();

	while(true)
		sleep(3600);
	return 0;
}