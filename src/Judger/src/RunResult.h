//
// Created by torapture on 17-11-12.
//

#ifndef JUDGER_RUNRESULT_H
#define JUDGER_RUNRESULT_H

#include <string>

class RunResult {
public:
	static const RunResult JUDGE_ERROR;
	static const RunResult COMPILE_ERROR;
	static const RunResult COMPILE_SUCCESS;

	static const RunResult TIME_LIMIT_EXCEEDED;
	static const RunResult MEMORY_LIMIT_EXCEEDED;
	static const RunResult RUNTIME_ERROR;
	static const RunResult OUTPUT_LIMIT_EXCEEDED;
	static const RunResult RESTRICTED_FUNCTION;

	static const RunResult RUN_SUCCESS;

	static const RunResult ACCEPTED;
	static const RunResult PRESENTATION_ERROR;
	static const RunResult WRONG_ANSWER;

	static const RunResult SEND_TO_JUDGE;
	static const RunResult SEND_TO_REJUDGE;
	static const RunResult QUEUEING;
	static const RunResult COMPILING;
	static const RunResult RUNNING;

	int time_used_ms;
	int memory_used_kb;
	std::string status;
	std::string ce_info;
	RunResult() {}
	RunResult(int time_used_ms, int memory_used_kb, std::string status, std::string ce_info):
			time_used_ms(time_used_ms), memory_used_kb(memory_used_kb),
            status(status), ce_info(ce_info)  {}
public:
	bool operator == (const RunResult &rhs) const {
		return this->status == rhs.status;
	}
	bool operator != (const RunResult &rhs) const {
		return !(*this == rhs);
	}
	RunResult set_time_used(int time_used_ms) const {
		RunResult result = *this;
		result.time_used_ms = time_used_ms;
		return result;
	}
	RunResult set_memory_used(int memory_used_kb) const {
		RunResult result = *this;
		result.memory_used_kb = memory_used_kb;
		return result;
	}
	RunResult set_ce_info(const std::string &ce_info) const {
		if (*this == COMPILE_ERROR) {
			RunResult result = *this;
			result.ce_info = ce_info;
			return result;
		} else {
			return *this;
		}
	}
	std::string get_print_string() const {
		std::string res;
		res += '[';
		if (*this == COMPILE_ERROR) res += "\n\t";
		res += "status: " + status + ", ";
		res += "time_used: " + std::to_string(time_used_ms) + "ms, ";
		res += "memory_used: " + std::to_string(memory_used_kb) + "kb";
		if (*this == COMPILE_ERROR)
			res += ",\n\tce_info:\n\t" + ce_info;
		if (*this == COMPILE_ERROR) res += "\n";
		res += ']';
		return res;
	}
};


#endif //JUDGER_RUNRESULT_H
