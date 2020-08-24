#!/bin/bash

CURDIR=$(cd $(dirname ${BASH_SOURCE[0]}); pwd)
IMAGE_NAME="mysql:5.7"
CONTAINER_NAME="nenuoj-mysql"

if [ ! -n "$MYSQL_ROOT_PASSWORD" ]; then
  echo "Please set environment variable MYSQL_ROOT_PASSWORD"
  exit 1
fi

if [ ! -n "$MYSQL_DATADIR" ]; then
  echo "Please set environment variable MYSQL_DATADIR"
  exit 1
fi

docker container rm -f $CONTAINER_NAME 1>/dev/null 2>&1

docker run \
  --name $CONTAINER_NAME \
  --restart always \
  -v $MYSQL_DATADIR:/var/lib/mysql \
  -v $CURDIR/mysql.cnf:/etc/mysql/mysql.cnf \
  -e MYSQL_ROOT_PASSWORD="$MYSQL_ROOT_PASSWORD" \
  --net nenuoj-net \
  -d $IMAGE_NAME
