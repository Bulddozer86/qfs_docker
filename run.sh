#!/usr/bin/env bash

source ./bash/variables.sh

echo ">> Start building of docker images....";

for i in ${ARRAY_CUSTOM_DOCKER_IMAGES[@]}; do
  docker images | grep ${i}
  greprc=$?

  if [[ ! $greprc -eq 0 ]]; then
    echo "Need building"
  else
    echo "Images ${i} installed already"
  fi

done
