#!/bin/bash

IMAGE_NAME="memcached"
CONTAINER_NAME="nenuoj-memcached"

docker container rm -f $CONTAINER_NAME 1>/dev/null 2>&1
docker run \
  --name $CONTAINER_NAME \
  --restart always \
  --net nenuoj-net \
  -d $IMAGE_NAME \
  memcached -m 128
