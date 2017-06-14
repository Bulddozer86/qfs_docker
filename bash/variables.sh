#!/usr/bin/env bash

#GENERAL
SCRIPT_DIR=`pwd -P`
ROOT_DIR="$(dirname "$SCRIPT_DIR")/"

#MESAGES COLORS
TEXT_COLOR='\e[97m'

#DOCKER IMAGES
IMAGE_NGINX="nginx:latest"
IMAGE_PHP="php_qfs"
IMAGE_MONGO="mongo"
IMAGE_ELASTICSEARCH="elasticsearch_qfs"
IMAGE_NODE="node_qfs"

#DOCKER NAMES
D_MONGO="qfs_mongo"
D_ELASTICSEARCH="qfs_elasticsearch"
D_FRONTEND="qfs_frontend"
D_SERVER="qfs_nginx"
D_PHP="qfs_php"
