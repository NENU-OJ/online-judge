#include <iostream>
#include <cassert>
#include <glog/logging.h>
#include <sys/time.h>
#include <sys/resource.h>
#include <wait.h>

#include "Runner.h"
#include "Config.h"

using namespace std;


int main(int argc, const char *argv[]) {
//	itimerval itv;
//	itv.it_value.tv_sec = 4;
//	itv.it_value.tv_usec = 0;
//	itv.it_interval.tv_sec = 0;
//	itv.it_interval.tv_usec = 0;
//	setitimer(ITIMER_REAL, &itv, NULL);
//	rlimit rlim;
//	rlim.rlim_cur = rlim.rlim_max = 6;
//	setrlimit(RLIMIT_CPU, &rlim);
//	pid_t pid;
//	if ((pid = fork()) == 0) {
//		cout << pid << endl;
//		puts("child");
//		execl("/home/torapture/a.out", "a.out", NULL);
//	} else {
//		cout << pid << endl;
//		puts("father");
//		int s;
//		wait(&s);
//	}
	Runner run;
	RunResult result = run.compile();
	cout << result.get_print_string() << endl;
	return 0;
}