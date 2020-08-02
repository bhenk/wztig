#!/usr/bin/env bash

cd `dirname $0`

. stop-server.sh

docker system prune --volumes
docker container prune
docker image prune -a
docker volume prune
docker network prune