//
// Created by torapture on 17-11-14.
//

#ifndef JUDGER_UTILS_H
#define JUDGER_UTILS_H

#include <cstdio>
#include <string>

class Utils {
public:
	static bool save_to_file(const std::string &file, const std::string &content);
	static std::string get_content_from_file(const std::string &file_name);
	static void delete_file(const std::string &file_name);
	static bool check_file(const std::string &file_name);
	static std::string get_input_file(int pid);
	static std::string get_output_file(int pid);
	static std::string get_user_output_file();
};


#endif //JUDGER_UTILS_H
