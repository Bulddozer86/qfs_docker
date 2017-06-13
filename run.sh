#!/usr/bin/env bash

source ./bash/variables.sh

function error_msg {
  echo -e "\n${TEXT_COLOR}\e[41m \n $1 \n \e[49m\n"
}

function notice_msg {
  echo -e "\n${TEXT_COLOR}\e[43m \n $1 \n \e[49m\n"
}

function success_msg {
  echo -e "\n${TEXT_COLOR}\e[42m \n $1 \n \e[49m\n"
}

function info_msg {
  echo -e "\n${TEXT_COLOR}\e[44m \n $1 \n \e[49m\n"
}

IMAGES=(${!ARRAY_CUSTOM_DOCKER_IMAGES[@]})

info_msg ">> Start building of docker images....";

for (( i=0; $i < ${#ARRAY_CUSTOM_DOCKER_IMAGES[@]}; i+=1 )); do
    IMAGE=${IMAGES[$i]};

    grep_result=$(docker images | grep ${IMAGE})
    greprc=$?

    if [[ ! $greprc -eq 0 ]]; then
        notice_msg "Start building image container '${IMAGE}'"
        cd ${ARRAY_CUSTOM_DOCKER_IMAGES[$IMAGE]}
        docker build -t=${IMAGE} .
        cd ..
        success_msg "Docker '${IMAGE}' was builded successful"
    else
        info_msg "Images '${IMAGE}' builded already!"
    fi
done

docker-compose up -d

is_docker_php=$(docker ps | grep ${D_PHP})
is_docker_php=$?

is_docker_server=$(docker ps | grep ${D_SERVER})
is_docker_server=$?

if [[ ! $is_docker_php -eq 0 ]]; then
    error_msg "Docker '${D_PHP}' not run at the moment"
    docker ps -a
    exit 0
fi

if [[ ! $is_docker_server -eq 0 ]]; then
    error_msg "Docker '${D_SERVER}' not run at the moment"
    docker ps -a
    exit 0
fi

docker exec -t -i $D_PHP php bin/console parser:run
docker exec -t -i $D_PHP php bin/console cleaner:run
docker exec -t -i $D_PHP php bin/console flat:download
#php bin/console fos:elastica:populate

#docker exec -it nginx-server nginx -s reload
