//
// Created by torapture on 17-11-12.
//

#include <iostream>
#include <fstream>
#include <map>

#include "Config.h"


Config * Config::instance = new Config("config.ini");

Config::Config(std::string config_file) {
	std::ifstream file(config_file.c_str());
	if (!file) {
		std::cerr << config_file + " dose not exists" << std::endl;
		exit(1);
	} else {
		std::string key, eq, value;
		while (file >> key >> eq >> value) {
			config_map.insert({key, value});
		}
		if (config_map.find("ip") == config_map.end()) exit(1);
		if (config_map.find("port") == config_map.end()) exit(1);
		if (config_map.find("low_privilege_uid") == config_map.end()) exit(1);
		if (config_map.find("compile_time_ms") == config_map.end()) exit(1);
		if (config_map.find("compile_memory_kb") == config_map.end()) exit(1);
		if (config_map.find("spj_run_time_ms") == config_map.end()) exit(1);
		if (config_map.find("spj_memory_kb") == config_map.end()) exit(1);
		if (config_map.find("source_file") == config_map.end()) exit(1);
		if (config_map.find("binary_file") == config_map.end()) exit(1);
		if (config_map.find("input_file") == config_map.end()) exit(1);
		if (config_map.find("output_file") == config_map.end()) exit(1);

		ip = config_map["ip"];
		port = atoi(config_map["port"].c_str());
		low_privilege_uid = atoi(config_map["low_privilege_uid"].c_str());
		compile_time_ms = atoi(config_map["compile_time_ms"].c_str());
		compile_memory_kb = atoi(config_map["compile_memory_kb"].c_str());
		spj_run_time_ms = atoi(config_map["spj_run_time_ms"].c_str());
		spj_memory_kb = atoi(config_map["spj_memory_kb"].c_str());
		source_file = config_map["source_file"];
		binary_file = config_map["binary_file"];
		input_file = config_map["input_file"];
		output_file = config_map["output_file"];
	}
}