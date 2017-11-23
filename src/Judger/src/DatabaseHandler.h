//
// Created by torapture on 17-11-20.
//

#ifndef JUDGER_DATABASEHANDLER_H
#define JUDGER_DATABASEHANDLER_H

#include <string>
#include <map>
#include <mysql.h>
#include <vector>
#include "RunResult.h"

class DatabaseHandler {
private:
	MYSQL *mysql;
public:
	DatabaseHandler();
	~DatabaseHandler();
	std::vector<std::map<std::string, std::string>> get_all_result(const std::string &query);
	std::map<std::string, std::string> get_run_stat(int runid);
	std::map<std::string, std::string> get_problem_description(int pid);
	std::vector<std::map<std::string, std::string>> get_unfinished_results();
	void change_run_result(int runid, const RunResult &result);
	void add_problem_result(int pid, const RunResult &result);
	void add_user_accepted(int uid);
	void update_query(const std::string &query);
};


#endif //JUDGER_DATABASEHANDLER_H
