//
// Created by torapture on 17-11-12.
//

#include "RunResult.h"

const RunResult RunResult::JUDGE_ERROR = RunResult(0, 0, "Judge Error", "");
const RunResult RunResult::COMPILE_ERROR = RunResult(0, 0, "Compile Error", "");
const RunResult RunResult::COMPILE_SUCCESS = RunResult(0, 0, "Compile Success", "");
const RunResult RunResult::TIME_LIMIT_EXCEEDED = RunResult(0, 0, "Time Limit Exceeded", "");
const RunResult RunResult::MEMORY_LIMIT_EXCEEDED = RunResult(0, 0, "Memory Limit Exceeded", "");
const RunResult RunResult::RUNTIME_ERROR = RunResult(0, 0, "Runtime Error", "");
const RunResult RunResult::OUTPUT_LIMIT_EXCEEDED = RunResult(0, 0, "Output Limit Exceeded", "");
const RunResult RunResult::ACCEPTED = RunResult(0, 0, "Accepted", "");
const RunResult RunResult::PRESENTATION_ERROR = RunResult(0, 0, "Presentation Error", "");
const RunResult RunResult::WRONG_ANSWER = RunResult(0, 0, "Wrong Answer", "");
const RunResult RunResult::RUN_SUCCESS = RunResult(0, 0, "Run Success", "");
const RunResult RunResult::SEND_TO_JUDGE = RunResult(0, 0, "Send to Judge", "");
const RunResult RunResult::SEND_TO_REJUDGE = RunResult(0, 0, "Send to Rejudge", "");
const RunResult RunResult::QUEUEING = RunResult(0, 0, "Queueing", "");
const RunResult RunResult::COMPILING = RunResult(0, 0, "Compiling", "");
const RunResult RunResult::RUNNING = RunResult(0, 0, "Running", "");
const RunResult RunResult::RESTRICTED_FUNCTION = RunResult(0, 0, "Restricted Function", "");