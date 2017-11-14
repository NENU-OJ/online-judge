//
// Created by torapture on 17-11-12.
//

#ifndef JUDGER_CONFIG_H
#define JUDGER_CONFIG_H


#include <iostream>
#include <cassert>
#include <map>


class Config {
private:

	static Config *instance;

	Config(std::string config_file);

	std::map<int, std::string> src_extension;
	std::map<int, std::string> exc_extension;

	std::map<std::string, std::string> config_map;

	std::string ip;
	int port;
	int low_privilege_uid;
	int compile_time_ms;
	int compile_memory_kb;
	int spj_run_time_ms;
	int spj_memory_kb;
	std::string temp_path;
	std::string source_file;
	std::string binary_file;
	std::string output_file;
	std::string ce_info_file;

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
	const std::string &get_ip() const {
		return ip;
	}

	int get_port() const {
		return port;
	}

	int get_low_privilege_uid() const {
		return low_privilege_uid;
	}

	int get_compile_time_ms() const {
		return compile_time_ms;
	}

	int get_compile_memory_kb() const {
		return compile_memory_kb;
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

	const std::string &get_ce_info_file() const {
		return ce_info_file;
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

};


#endif //JUDGER_CONFIG_H
