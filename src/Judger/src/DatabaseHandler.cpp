//
// Created by torapture on 17-11-20.
//

#include <cstring>
#include <glog/logging.h>
#include "DatabaseHandler.h"
#include "Exception.h"
#include "Config.h"

DatabaseHandler::DatabaseHandler() {
	mysql = new MYSQL;
	mysql_init(mysql);
	bool reconnect_flag = true;
	mysql_options(mysql, MYSQL_OPT_RECONNECT, &reconnect_flag);
	mysql_options(mysql, MYSQL_SET_CHARSET_NAME, "utf8");
	if (!mysql_real_connect(mysql, Config::get_instance()->get_db_ip().c_str(),
	                        Config::get_instance()->get_db_user().c_str(),
	                        Config::get_instance()->get_db_password().c_str(),
	                        Config::get_instance()->get_db_db_name().c_str(),
	                        Config::get_instance()->get_db_port(),
	                        NULL, 0)) {
		throw Exception("Cannot connect to DB");
	}
}

DatabaseHandler::~DatabaseHandler() {
	mysql_close(mysql);
	delete mysql;
}

std::vector<std::map<std::string, std::string>> DatabaseHandler::get_all_result(const std::string &query) {
	std::vector<std::map<std::string, std::string>> result;

	mysql_ping(mysql);
	mysql_query(mysql, query.c_str());

	MYSQL_RES *res = mysql_use_result(mysql);
	MYSQL_FIELD *fields = mysql_fetch_field(res);
	MYSQL_ROW row;
	int num_fields = mysql_num_fields(res);

	while (row = mysql_fetch_row(res)) {
		std::map<std::string, std::string> result_map;
		for (int i = 0; i < num_fields; ++i)
			result_map[fields[i].name] = row[i];
		result.push_back(result_map);
	}

	mysql_free_result(res);

	return result;
}

std::map<std::string, std::string> DatabaseHandler::get_run_stat(int runid) {
	std::string query = "SELECT id, problem_id, source, user_id, language_id "
			            "FROM t_status "
			            "WHERE id = " + std::to_string(runid);
	auto result = get_all_result(query);

	if (result.empty())
		throw Exception("fucking runid: " + std::to_string(runid) + " does not exist");

	return result[0];
}

std::map<std::string, std::string> DatabaseHandler::get_problem_description(int pid) {
	std::string query = "SELECT id, time_limit, memory_limit, is_special_judge "
						"FROM t_problem "
						"WHERE id = " + std::to_string(pid);

	auto result = get_all_result(query);
	if (result.empty())
		throw Exception("fucking pid: " + std::to_string(pid) + " does not exist");
	return result[0];
}


std::vector<std::map<std::string, std::string>> DatabaseHandler::get_unfinished_results() {
	std::string query = "SELECT id, problem_id, source, user_id, language_id "
						"FROM t_status "
						"WHERE "
						"result = '" + RunResult::SEND_TO_JUDGE.status + "' OR "
						"result = '" + RunResult::SEND_TO_REJUDGE.status + "' OR "
						"result = '" + RunResult::QUEUEING.status + "' OR "
						"result = '" + RunResult::COMPILING.status + "' OR "
						"result = '" + RunResult::RUNNING.status + "'";
	return get_all_result(query);
}

void DatabaseHandler::change_run_result(int runid, const RunResult &result) {

	int time_used_ms = result.time_used_ms;
	int memory_used_kb = result.memory_used_kb;

	std::string query = "UPDATE t_status SET "
			                    "result = '" + result.status + "', "
			                    "time_used = " + std::to_string(result.time_used_ms) + ", "
			                    "memory_used = " + std::to_string(result.memory_used_kb) + ", "
			                    "ce_info = '" + escape(result.ce_info) + "' "
			                    "WHERE id = " + std::to_string(runid);
	update_query(query);
}

void DatabaseHandler::add_problem_result(int pid, const RunResult &result) {
	std::string field;
	if (result == RunResult::ACCEPTED) field = "total_ac";
	else if (result == RunResult::WRONG_ANSWER) field = "total_wa";
	else if (result == RunResult::RUNTIME_ERROR) field = "total_re";
	else if (result == RunResult::COMPILE_ERROR) field = "total_ce";
	else if (result == RunResult::TIME_LIMIT_EXCEEDED) field = "total_tle";
	else if (result == RunResult::MEMORY_LIMIT_EXCEEDED) field = "total_mle";
	else if (result == RunResult::PRESENTATION_ERROR) field = "total_pe";
	else if (result == RunResult::OUTPUT_LIMIT_EXCEEDED) field = "total_ole";
	else if (result == RunResult::RESTRICTED_FUNCTION) field = "total_rf";
	if (!field.empty()) {
		std::string query = "UPDATE t_problem "
							"SET " + field + " = " + field + " + 1 "
							"WHERE id = " + std::to_string(pid);
		update_query(query);
	}
}

void DatabaseHandler::add_user_total_accepted(int uid) {
	std::string query = "UPDATE t_user "
						"SET total_ac = total_ac + 1 "
						"WHERE id = " + std::to_string(uid);
	update_query(query);
}

void DatabaseHandler::update_query(const std::string &query) {
	mysql_ping(mysql);
	mysql_query(mysql, query.c_str());
}

/**
 * Do mysql_real_escape on the string
 * @param str   Original string
 * @return Escaped string
 */
std::string DatabaseHandler::escape(std::string str) {
	char * res = new char[str.length() * 2 + 1];
	mysql_real_escape_string(mysql, res, str.c_str(), str.length());
	str = res;
	delete [] res;
	return str;
}

bool DatabaseHandler::already_accepted(int uid, int pid) {
	std::string query = "SELECT id FROM t_status "
						"WHERE "
						"user_id = " + std::to_string(uid) + " AND "
						"problem_id = " + std::to_string(pid) + " AND "
						"result = 'Accepted' "
						"LIMIT 1";
	auto result = get_all_result(query);
	return !result.empty();
}

void DatabaseHandler::add_user_total_solved(int uid) {
	std::string query = "UPDATE t_user "
						"SET solved_problem = solved_problem + 1 "
						"WHERE id = " + std::to_string(uid);
	update_query(query);
}
