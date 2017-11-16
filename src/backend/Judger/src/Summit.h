//
// Created by torapture on 17-11-16.
//

#ifndef JUDGER_SUMMIT_H
#define JUDGER_SUMMIT_H

#include <string>

class Summit {
private:
	int runid;
	bool is_spj;
	std::string input_file;
	std::string output_file;
	std::string user_output_file;


public:
	Summit();
	Summit(int runid);
	void work();
};


#endif //JUDGER_SUMMIT_H
