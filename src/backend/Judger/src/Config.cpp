//
// Created by torapture on 17-11-12.
//

#include <iostream>
#include <fstream>
#include <map>

#include "Config.h"
#include "Runner.h"
#include <glog/logging.h>

Config * Config::instance = new Config("config.ini");

const int Config::CPP_LANG = 0;
const int Config::CPP11_LANG = 1;
const int Config::JAVA_LANG = 2;
const int Config::PY2_LANG = 3;
const int Config::PY3_LANG = 4;

Config::Config(std::string config_file) {

	FLAGS_logtostderr = true;
	FLAGS_logtostderr = true;

	std::ifstream file(config_file.c_str());

	if (!file) {
		LOG(FATAL) << "[Config::Config] [shutdown] config file " + config_file + " does not exists";
	} else {

		src_extension[CPP_LANG] = ".cpp";
		src_extension[CPP11_LANG] = ".cpp";
		src_extension[JAVA_LANG] = ".java";
		src_extension[PY2_LANG] = ".py";
		src_extension[PY3_LANG] = ".py";

		exc_extension[CPP_LANG] = ".out";
		exc_extension[CPP11_LANG] = ".out";
		exc_extension[JAVA_LANG] = "";
		exc_extension[PY2_LANG] = ".pyc";
		exc_extension[PY3_LANG] = ".pyc";


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
		if (config_map.find("output_file") == config_map.end()) exit(1);
		if (config_map.find("ce_info_file") == config_map.end()) exit(1);
		if (config_map.find("temp_path") == config_map.end()) exit(1);

		ip = config_map["ip"];
		port = atoi(config_map["port"].c_str());
		low_privilege_uid = atoi(config_map["low_privilege_uid"].c_str());
		compile_time_ms = atoi(config_map["compile_time_ms"].c_str());
		compile_memory_kb = atoi(config_map["compile_memory_kb"].c_str());
		spj_run_time_ms = atoi(config_map["spj_run_time_ms"].c_str());
		spj_memory_kb = atoi(config_map["spj_memory_kb"].c_str());
		source_file = config_map["source_file"];
		binary_file = config_map["binary_file"];
		output_file = config_map["output_file"];
		ce_info_file = config_map["ce_info_file"];
		temp_path = config_map["temp_path"];

		LOG(INFO) << "config over";
	}
}