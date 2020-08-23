#!/bin/bash

CURDIR=$(cd $(dirname ${BASH_SOURCE[0]}); pwd)
IMAGE_NAME="nenuoj-web"
CONTAINER_NAME="nenuoj-web"

if [ ! -n "$WEB_HTTP_PORT" ]; then
  echo "Please set environment variable WEB_HTTP_PORT"
  exit 1
fi

if [ ! -n "$NGINX_SITES_ENABLE_DIR" ]; then
  echo "Please set environment variable NGINX_SITES_ENABLE_DIR"
  exit 1
fi

docker container rm -f $CONTAINER_NAME 1>/dev/null 2>&1

docker run \
  -d \
  -it \
  --name=$CONTAINER_NAME \
  --net nenuoj-net \
  -p $WEB_HTTP_PORT:80 \
  -v $CURDIR/php/php.ini:/etc/php/7.0/fpm/php.ini \
  -v $NGINX_SITES_ENABLE_DIR:/etc/nginx/sites-enabled \
  $IMAGE_NAME
