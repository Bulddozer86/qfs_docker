#!/usr/bin/env bash

source ./bash/variables.sh

IMAGES=(${!ARRAY_CUSTOM_DOCKER_IMAGES[@]})

echo ">> Start building of docker images....";

for (( I=0; $I < ${#ARRAY_CUSTOM_DOCKER_IMAGES[@]}; I+=1 )); do
    IMAGE=${IMAGES[$I]};

    docker images | grep ${IMAGE}
    greprc=$?

    if [[ ! $greprc -eq 0 ]]; then
      echo "Need building image ${IMAGE}"
    else
      echo "Images ${IMAGE} installed already!"
      cd ${ARRAY_CUSTOM_DOCKER_IMAGES[$IMAGE]}
#      TODO:: will add docker build there
      cd ..

    fi
#    echo $IMAGE --- ${ARRAY_CUSTOM_DOCKER_IMAGES[$IMAGE]};
done


