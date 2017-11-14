//
// Created by torapture on 17-11-14.
//

#include <iostream>
#include <fstream>
#include "Utils.h"

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

std::string Utils::get_content_from_file(const std::string &file) {
	std::string result;
	std::ifstream fs(file.c_str());
	if (fs) {
		std::string buffer;
		while (getline(fs, buffer))
			result += buffer + "\n";
	}
	return result;
}
