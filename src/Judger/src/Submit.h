//
// Created by torapture on 17-11-16.
//

#ifndef JUDGER_SUBMIT_H
#define JUDGER_SUBMIT_H

#include <string>
#include "RunResult.h"

class Submit {
private:
	int runid;
	int uid;
	int pid;
	int time_limit_ms;
	int memory_limit_kb;
	int language;
	int is_spj;
	std::string std_input_file;
	std::string std_output_file;
	std::string user_output_file;
	std::string src;
private:
	RunResult spj_check();
	RunResult normal_check();
public:
	Submit();
	void set_runid(int runid);
	void set_pid(int pid);
	void set_uid(int uid);
	void set_time_limit_ms(int time_limit_ms);
	void set_memory_limit_kb(int memory_limit_kb);
	void set_language(int language);
	void set_is_spj(int is_spj);
	void set_std_input_file(const std::string &std_input_file);
	void set_std_output_file(const std::string &std_output_file);
	void set_user_output_file(const std::string &user_output_file);
	void set_src(const std::string &src);
	void work();
	static Submit * get_from_runid(int runid);

	int get_runid() const {
		return runid;
	}
};


#endif //JUDGER_SUBMIT_H
