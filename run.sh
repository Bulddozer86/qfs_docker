#!/usr/bin/env bash

source ./bash/variables.sh

IMAGES=(${!ARRAY_CUSTOM_DOCKER_IMAGES[@]})

echo ">> Start building of docker images....";

for (( i=0; $i < ${#ARRAY_CUSTOM_DOCKER_IMAGES[@]}; i+=1 )); do
    IMAGE=${IMAGES[$i]};

    grep_result=$(docker images | grep ${IMAGE})
    greprc=$?

    if [[ ! $greprc -eq 0 ]]; then
        echo "Start building image container ${IMAGE}"
        cd ${ARRAY_CUSTOM_DOCKER_IMAGES[$IMAGE]}
        docker build -t=${IMAGE} .
        cd ..
    else
        echo "Images ${IMAGE} builded already!"
    fi
#    echo $IMAGE --- ${ARRAY_CUSTOM_DOCKER_IMAGES[$IMAGE]};
done


