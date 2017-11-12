//
// Created by torapture on 17-11-12.
//

#ifndef JUDGER_JUDGER_H
#define JUDGER_JUDGER_H

#include <string>
#include <unistd.h>
#include <iostream>
#include "RunResult.h"

class Runner {
public:
	static const int DEFAULT_CPP_TIME_LIMIT_MS = 1000;
	static const int DEFAULT_CPP_MEMORY_LIMIT_KB = 32768;
	static const int DEFAULT_OTHER_TIME_LIMIT_MS = 2000;
	static const int DEFAULT_OTHER_MEMORY_LIMIT_KB = 65536;
	static const int DEFAULT_STACK_LIMIT_KB = 32768;
	static const int DEFAULT_OUTPUT_LIMIT_KB = 128 * 1024;

	static const int CPP_LANG = 0;
	static const int CPP11_LANG = 1;
	static const int JAVA_LANG = 2;
	static const int PY2_LANG = 3;
	static const int PY3_LANG = 4;

private:
	int cpp_time_limit_ms;
	int cpp_memory_limit_kb;
	int other_time_limit_ms;
	int other_memory_limit_kb;
	int stack_limit_kb;
	int output_limit_kb;

	int language;
	std::string src;

public:
	Runner();
	Runner(int cpp_time_limit_ms, int other_time_limit_ms,
	       int cpp_memory_limit_kb, int other_memory_limit_kb,
	       int stack_limit_kb, int output_limit_kb);
private:
	void child_compile();
	void child_run();
public:
	RunResult compile();
	RunResult run();
};


#endif //JUDGER_JUDGER_H
