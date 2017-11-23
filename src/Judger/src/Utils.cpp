//
// Created by torapture on 17-11-14.
//

#include <iostream>
#include <fstream>
#include "Utils.h"
#include "Config.h"

/**
 * save content to file
 * @param file
 * @param content
 * @return true if save successful, else false
 */
bool Utils::save_to_file(const std::string &file, const std::string &content) {
	FILE *fp = fopen(file.c_str(), "w");
	while (fp == NULL) fp = fopen(file.c_str(), "w");
	if (fp != NULL) {
		fputs(content.c_str(), fp);
		fclose(fp);
		return true;
	}
	return false;
}

std::string Utils::get_content_from_file(const std::string &file_name) {
	int lines = 0, tried = 0;
	std::string res = "", tmps;
	std::fstream fin(file_name.c_str(), std::fstream::in);
	while (fin.fail() && tried < 10) {
		tried++;
		fin.open(file_name.c_str(), std::fstream::in);
		return res;
	}
	if (fin.fail()) return res;
	while (getline(fin, tmps)) {
		if (res != "") res += "\n";
		res += tmps;
		lines++;
		if (fin.eof()) break;
	}
	fin.close();
	return res;
}

void Utils::delete_file(const std::string &file_name) {
	if (file_name == "*") return;
	system(("rm -f " + file_name).c_str());
}

bool Utils::check_file(const std::string &file_name) {
	int tried = 0;
	if (file_name == "") return false;
	FILE * fp = fopen(file_name.c_str(), "r");
	while (fp == NULL && tried < 5) {
		tried++;
		fp = fopen(file_name.c_str(), "r");
	}
	if (fp != NULL) {
		fclose(fp);
		return true;
	}
	return false;
}

std::string Utils::get_input_file(int pid) {
	return Config::get_instance()->get_test_files_path() +
			std::to_string(pid) + "/" + "input";
}

std::string Utils::get_output_file(int pid) {
	return Config::get_instance()->get_test_files_path() +
	       std::to_string(pid) + "/" + "output";
}

std::string Utils::get_user_output_file() {
	return Config::get_instance()->get_temp_path() +
           Config::get_instance()->get_output_file();
}
