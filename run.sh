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

declare -A ARRAY_CUSTOM_DOCKER_IMAGES
ARRAY_CUSTOM_DOCKER_IMAGES=([$IMAGE_PHP]='php' [$IMAGE_ELASTICSEARCH]='elasticsearch' [$IMAGE_NODE]='node')

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


info_msg 'Run dockers please waiting ... '

ARRAY_DOCKER_NAMES=($D_MONGO $D_ELASTICSEARCH $D_FRONTEND $D_SERVER $D_PHP)
COUNTER=30
INDEX=0

while true; do
    if [[ ${COUNTER} -eq 0 ]]; then
         error_msg "Cannot run all required dockers"
         exit 0
    fi

    if [[ ${#ARRAY_DOCKER_NAMES[@]} -eq $((${INDEX})) ]]; then
         success_msg "All required dockers was run"
         break
    fi

    for i in ${ARRAY_DOCKER_NAMES[@]}; do
        grep_result=$(docker ps | grep ${i})
        greprc=$?

        if [[ $greprc -eq 0 ]]; then
            let INDEX+=1
        fi
        sleep 1
    done
    let COUNTER-=1
done

#docker exec -t -i $D_PHP php bin/console parser:run
#docker exec -t -i $D_PHP php bin/console cleaner:run
#docker exec -t -i $D_PHP php bin/console flat:download
#php bin/console fos:elastica:populate

#docker exec -it nginx-server nginx -s reload
