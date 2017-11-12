cmake_minimum_required(VERSION 3.7)
project(Judger)

set(CMAKE_CXX_STANDARD 11)

set(SOURCE_FILES src/main.cpp src/Runner.cpp src/Runner.h src/RunResult.h src/RunResult.cpp src/Config.cpp src/Config.h)
add_executable(Judger ${SOURCE_FILES})