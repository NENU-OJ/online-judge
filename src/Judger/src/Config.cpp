//
// Created by torapture on 17-11-12.
//

#include <iostream>
#include <fstream>
#include <map>
#include <sys/syscall.h>
#include <glog/logging.h>

#include "Config.h"

Config * Config::instance = new Config("config.ini");

const int Config::CPP_LANG = 1;
const int Config::CPP11_LANG = 2;
const int Config::JAVA_LANG = 3;
const int Config::PY2_LANG = 4;
const int Config::PY3_LANG = 5;

Config::Config(std::string config_file) {

	FLAGS_logtostderr = true;

	std::ifstream file(config_file.c_str());

	if (!file) {
		LOG(FATAL) << "config file " + config_file + " does not exists";
	} else {

		restricted_call[CPP_LANG] = restricted_call[CPP11_LANG] = restricted_call[PY2_LANG] = restricted_call[PY3_LANG] = {
			SYS__sysctl,
			SYS_chdir,
			SYS_chmod,
			SYS_chown,
			SYS_chroot,
			SYS_clone,
			SYS_creat,
			SYS_create_module,
			SYS_delete_module,
			SYS_execve,
			SYS_fork,
			SYS_getpgrp,
			SYS_kill,
			SYS_mkdir,
			SYS_mknod,
			SYS_mount,
			SYS_rmdir,
			SYS_ptrace,
			SYS_reboot,
			SYS_rename,
			SYS_restart_syscall,
			SYS_select,
			SYS_setgid,
			SYS_setitimer,
			SYS_setgroups,
			SYS_sethostname,
			SYS_setrlimit,
			SYS_setuid,
			SYS_settimeofday,
			SYS_tkill,
			SYS_vfork,
			SYS_vhangup,
			SYS_vserver,
			SYS_wait4,
			SYS_clock_nanosleep,
			SYS_nanosleep,
			SYS_pause,
#ifndef __i386__
			SYS_accept,
			SYS_bind,
			SYS_connect,
			SYS_listen,
			SYS_socket
#else
			SYS_signal,
			SYS_waitpid,
			SYS_nice,
			SYS_waitpid,
			SYS_umount,
			SYS_socketcall
#endif
		}; // end of restricted_call

		restricted_call[JAVA_LANG] = {
			SYS__sysctl,
			SYS_chdir,
			SYS_chmod,
			SYS_chown,
			SYS_chroot,
			SYS_creat,
			SYS_create_module,
			SYS_delete_module,
			SYS_execve,
			SYS_fork,
			SYS_getpgrp,
			SYS_kill,
			SYS_mkdir,
			SYS_mknod,
			SYS_mount,
			SYS_rmdir,
			SYS_ptrace,
			SYS_reboot,
			SYS_rename,
			SYS_restart_syscall,
			SYS_select,
			SYS_setgid,
			SYS_setitimer,
			SYS_setgroups,
			SYS_sethostname,
			SYS_setrlimit,
			SYS_setuid,
			SYS_settimeofday,
			SYS_tkill,
			SYS_vfork,
			SYS_vhangup,
			SYS_vserver,
			SYS_wait4,
			SYS_clock_nanosleep,
			SYS_nanosleep,
			SYS_pause,
#ifndef __i386__
			SYS_accept,
			SYS_bind,
			SYS_connect,
			SYS_listen,
			SYS_socket
#else
			SYS_signal,
			SYS_waitpid,
			SYS_nice,
			SYS_waitpid,
			SYS_umount,
			SYS_socketcall
#endif
		}; // end of restricted_call

		src_extension[CPP_LANG] = ".cpp";
		src_extension[CPP11_LANG] = ".cpp";
		src_extension[JAVA_LANG] = ".java";
		src_extension[PY2_LANG] = ".py";
		src_extension[PY3_LANG] = ".py";

		exc_extension[CPP_LANG] = ".out";
		exc_extension[CPP11_LANG] = ".out";
		exc_extension[JAVA_LANG] = ".class";
		exc_extension[PY2_LANG] = ".pyc";
		exc_extension[PY3_LANG] = ".pyc";


		std::string key, eq, value;
		while (file >> key >> eq >> value) {
			config_map.insert({key, value});
		}

		std::vector<std::string> check_list = {
			"listen_port",
			"db_ip",
			"db_port",
			"db_name",
			"db_user",
			"db_password",
			"low_privilege_uid",
			"compile_time_ms",
			"stack_limit",
			"spj_run_time_ms",
			"spj_memory_kb",
			"source_file",
			"binary_file",
			"output_file",
			"ce_info_file",
			"temp_path",
			"max_output_limit",
			"test_files_path",
			"spj_files_path",
			"stderr_file",
			"vm_multiplier"
		};

		for (const auto &key : check_list) {
			if (config_map.find(key) == config_map.end()) {
				LOG(FATAL) << "need key [" << key << "] in config.ini";
			}
		}

		listen_port = atoi(config_map["listen_port"].c_str());
		db_ip = config_map["db_ip"];
		db_port = atoi(config_map["db_port"].c_str());
		db_name = config_map["db_name"];
		db_user = config_map["db_user"];
		db_password = config_map["db_password"];
		low_privilege_uid = atoi(config_map["low_privilege_uid"].c_str());
		compile_time_ms = atoi(config_map["compile_time_ms"].c_str());
		stack_limit_kb = atoi(config_map["stack_limit"].c_str());
		spj_run_time_ms = atoi(config_map["spj_run_time_ms"].c_str());
		spj_memory_kb = atoi(config_map["spj_memory_kb"].c_str());
		source_file = config_map["source_file"];
		binary_file = config_map["binary_file"];
		output_file = config_map["output_file"];
		ce_info_file = config_map["ce_info_file"];
		temp_path = config_map["temp_path"];
		max_output_limit = atoi(config_map["max_output_limit"].c_str());
		vm_multiplier = atoi(config_map["vm_multiplier"].c_str());
		test_files_path = config_map["test_files_path"];
		spj_files_path = config_map["spj_files_path"];
		stderr_file = config_map["stderr_file"];
		LOG(INFO) << "config is finished";
	}
}