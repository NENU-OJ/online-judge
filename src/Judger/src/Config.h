//
// Created by torapture on 17-11-12.
//

#ifndef JUDGER_CONFIG_H
#define JUDGER_CONFIG_H


#include <iostream>
#include <cassert>
#include <map>
#include <vector>
#include <unordered_set>
#include <unordered_map>

class Config {
private:

	static Config *instance;

	Config(std::string config_file);

	std::map<int, std::string> src_extension;
	std::map<int, std::string> exc_extension;

	std::unordered_map<int, std::unordered_set<int>> restricted_call;

	std::map<std::string, std::string> config_map;

	int listen_port;
	std::string db_ip;
	int db_port;
	std::string db_name;
	std::string db_user;
	std::string db_password;
	int low_privilege_uid;
	int compile_time_ms;
	int stack_limit_kb;
	int spj_run_time_ms;
	int spj_memory_kb;
	int max_output_limit;
	int vm_multiplier;
	std::string temp_path;
	std::string test_files_path;
	std::string spj_files_path;
	std::string source_file;
	std::string binary_file;
	std::string output_file;
	std::string stderr_file;
	std::string ce_info_file;
	std::string connect_string;
public:
	static const int CPP_LANG;
	static const int CPP11_LANG;
	static const int JAVA_LANG;
	static const int PY2_LANG;
	static const int PY3_LANG;

public:
	static Config * get_instance() {
		return instance;
	}

	int get_listen_port() const {
		return listen_port;
	}

	const std::string &get_db_ip() const {
		return db_ip;
	}

	int get_db_port() const {
		return db_port;
	}

	const std::string &get_db_db_name() const {
		return db_name;
	}

	const std::string &get_db_user() const {
		return db_user;
	}

	const std::string &get_db_password() const {
		return db_password;
	}

	int get_low_privilege_uid() const {
		return low_privilege_uid;
	}

	int get_compile_time_ms() const {
		return compile_time_ms;
	}

	int get_stack_limit_kb() const {
		return stack_limit_kb;
	}

	int get_spj_run_time_ms() const {
		return spj_run_time_ms;
	}

	int get_spj_memory_kb() const {
		return spj_memory_kb;
	}

	const std::string &get_source_file() const {
		return source_file;
	}

	const std::string &get_temp_path() const {
		return temp_path;
	}

	const std::string &get_binary_file() const {
		return binary_file;
	}

	const std::string &get_output_file() const {
		return output_file;
	}

	const std::string &get_stderr_file() const {
		return stderr_file;
	}

	const std::string &get_ce_info_file() const {
		return ce_info_file;
	}

	const std::string &get_test_files_path() const {
		return test_files_path;
	}

	const std::string &get_spj_files_path() const {
		return spj_files_path;
	}

	const std::string &get_connect_string() const {
		return connect_string;
	}

	int get_max_output_limit() const {
		return max_output_limit;
	}

	int get_vm_multiplier() const {
		return vm_multiplier;
	}

	std::string get_src_extention(int lang) {
		return src_extension[lang];
	}

	std::string get_exc_extentino(int lang) {
		return exc_extension[lang];
	}

	std::string get_print_string() const {
		bool first = true;
		std::string res;
		res += "{\n";
		for (const auto &item : config_map) {
			if (!first) res += ",\n";
			first = false;
			res += "\t" + item.first + ": " + item.second;
		}
		res += "\n}";
		return res;
	}

	bool is_restricted_call(int language, int call) {
		return restricted_call[language].find(call) != restricted_call[language].end();
	}

};


#endif //JUDGER_CONFIG_H
