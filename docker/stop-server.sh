#!/usr/bin/env bash

cd `dirname $0`

. constants.env

docker ps
docker stop $CONTAINER_NAME

if [ $? -eq 0 ]; then
	echo "-- Server stopped"
else
	echo "## FAIL ######## see above for details ########"
	exit
fi

echo "-----------------------------------------"
echo "stopped $CONTAINER_NAME on http://localhost:$HOST_PORT"
echo "-----------------------------------------"
