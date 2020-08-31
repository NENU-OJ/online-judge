#!/bin/bash

CURDIR=$(cd $(dirname ${BASH_SOURCE[0]}); pwd)
IMAGE_NAME="nenuoj-web"
CONTAINER_NAME="nenuoj-web"
ARGS=""

if [ ! -n "$WEB_HTTP_PORT" ]; then
  echo "Please set environment variable WEB_HTTP_PORT"
  exit 1
fi

if [ ! -n "$NGINX_SITES_ENABLE_DIR" ]; then
  echo "Please set environment variable NGINX_SITES_ENABLE_DIR"
  exit 1
fi

if [ "$WEB_DEV" == "true" ]; then
  ARGS="$ARGS -e WEB_DEV=true"
  ARGS="$ARGS -v $CURDIR/../front:/web"
else
  if [ ! -n "$DB_CONFIG" ]; then
    echo "Please set environment variable DB_CONFIG"
    exit 1
  fi
  ARGS="$ARGS -v $DB_CONFIG:/web/config/db.php"

  if [ ! -n "$PARAMS_CONFIG" ]; then
    echo "Please set environment variable PARAMS_CONFIG"
    exit 1
  fi
  ARGS="$ARGS -v $PARAMS_CONFIG:/web/config/params.php"

  if [ ! -n "$CKFINDER_DIR" ]; then
    echo "Please set environment variable CKFINDER_DIR"
    exit 1
  fi
  ARGS="$ARGS -v $CKFINDER_DIR:/web/ckfinder"

  if [ ! -n "$USER_UPLOADS_DIR" ]; then
    echo "Please set environment variable USER_UPLOADS_DIR"
    exit 1
  fi
  ARGS="$ARGS -v $USER_UPLOADS_DIR:/web/uploads/avatar/user"
fi

docker container rm -f $CONTAINER_NAME 1>/dev/null 2>&1

docker run \
  -d \
  -it \
  --name=$CONTAINER_NAME \
  --net nenuoj-net \
  -p $WEB_HTTP_PORT:80 \
  -v $CURDIR/php/php.ini:/etc/php/7.0/fpm/php.ini \
  -v $CURDIR/php/www.conf:/etc/php/7.0/fpm/pool.d/www.conf \
  -v $NGINX_SITES_ENABLE_DIR:/etc/nginx/sites-enabled \
  -e USE_CDN=$USE_CDN \
  $ARGS \
  $IMAGE_NAME
