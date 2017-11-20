//
// Created by torapture on 17-11-20.
//

#ifndef JUDGER_EXCEPTION_H
#define JUDGER_EXCEPTION_H

#include <exception>
#include <string>

class Exception : std::exception {
public:
	Exception(const std::string &msg): msg(msg) {}
	~Exception() throw() {}
	const char *what() const throw() {
		return msg.c_str();
	}
private:
	std::string msg;
};

#endif //JUDGER_EXCEPTION_H
