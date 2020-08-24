#!/bin/bash

CURDIR=$(cd $(dirname ${BASH_SOURCE[0]}); pwd)

docker build -t nenuoj-web -f $CURDIR/Dockerfile $CURDIR/..