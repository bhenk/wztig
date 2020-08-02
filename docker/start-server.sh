#!/usr/bin/env bash

cd `dirname $0`

. constants.env

# build image if it does not exist
if [[ "$(docker images -q $DOCKER_IMAGE:latest 2> /dev/null)" == "" ]]; then
 	echo "-- Image $DOCKER_IMAGE does not exists. Building it"
	docker build -t $DOCKER_IMAGE .
else
 	echo "-- Image $DOCKER_IMAGE found"
fi

# remove existing and stopped container
if [ ! "$(docker ps -q -f name=$CONTAINER_NAME)" ]; then
	echo "-- Found existing container with name $CONTAINER_NAME"
    if [ "$(docker ps -aq -f status=exited -f name=$CONTAINER_NAME)" ]; then
        # cleanup
        docker rm $CONTAINER_NAME
        echo "-- Removed exited container $CONTAINER_NAME"
    fi
fi

# run the image
echo -e "\n-- Starting server with"
echo -e "\t /var/www/html \t\t-> $PUBLIC_DIR"
echo -e "\t /var/www/gitzwart \t-> $SRC_DIR"
echo -e "\t /var/www/data \t\t-> $DATA_DIR"
echo -e "\t /var/www/logs \t\t-> $LOGS_DIR"
echo -e "\t /var/www/vendor \t-> $VENDOR_DIR"

docker run -d \
	--name $CONTAINER_NAME \
	-v $PUBLIC_DIR:/var/www/html \
	-v $SRC_DIR:/var/www/gitzw \
	-v $DATA_DIR:/var/www/data \
	-v $LOGS_DIR:/var/www/logs \
	-v $VENDOR_DIR:/var/vendor \
	-p $HOST_PORT:80 \
	$DOCKER_IMAGE

if [ $? -eq 0 ]; then
	echo "-- Server started"
else
	echo "## FAIL ######## see above for details ########"
	exit
fi

docker ps


addr=$(ifconfig | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p')
echo "---------------------------------------------"
echo "now serving $CONTAINER_NAME on http://localhost:$HOST_PORT"
[ -z "$addr" ] && echo "--" || echo "-- and on network     http://$addr:$HOST_PORT"
echo "---------------------------------------------"

echo "Following console logs of $CONTAINER_NAME"
docker logs -f $CONTAINER_NAME
