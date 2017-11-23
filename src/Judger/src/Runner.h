//
// Created by torapture on 17-11-12.
//

#ifndef JUDGER_JUDGER_H
#define JUDGER_JUDGER_H

#include <string>
#include <iostream>
#include <unistd.h>
#include <sys/resource.h>

#include "RunResult.h"

class Runner {
public:
	static const int DEFAULT_TIME_LIMIT_MS = 1000;
	static const int DEFAULT_MEMORY_LIMIT_KB = 32768;

private:
	int time_limit_ms;
	int memory_limit_kb;

	int language;
	std::string src;

	std::string input_file;
	std::string src_file_name;
	std::string exc_file_name;


public:
	Runner();
	Runner(int time_limit_ms, int memory_limit_kb,
		   int language, const std::string &src);
public:
	static int get_time_ms(const rusage &run_info);
	static int get_memory_kb(const rusage &run_info);

private:
	void child_compile();
	void child_run();
public:
	RunResult compile();
	RunResult run(const std::string &input_file);
	RunResult run();
};


#endif //JUDGER_JUDGER_H
