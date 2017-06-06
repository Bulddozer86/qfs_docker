#!/usr/bin/env bash

#GENERAL
SCRIPT_DIR=`pwd -P`
ROOT_DIR="$(dirname "$SCRIPT_DIR")/"

#DOCKER IMAGES
IMAGE_NGINX="nginx:latest"
IMAGE_PHP="php_qfs"
IMAGE_MONGO="mongo"
IMAGE_ELASTICSEARCH="elasticsearch_qfs"
IMAGE_NODE="node_qfs"

declare -A ARRAY_CUSTOM_DOCKER_IMAGES
ARRAY_CUSTOM_DOCKER_IMAGES=([$IMAGE_PHP]='php' [$IMAGE_ELASTICSEARCH]='elasticsearch' [$IMAGE_NODE]='node')