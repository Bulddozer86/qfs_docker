#!/usr/bin/env bash

#GENERAL
SCRIPT_DIR=`pwd -P`
ROOT_DIR="$(dirname "$SCRIPT_DIR")/"

#MESAGES COLORS
TEXT_COLOR='\e[97m'

#DOCKER IMAGES
IMAGE_NGINX="nginx_qfs"
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

#PROXI SETTINGS
PROJECT_NAME="easy-flat-search"
DOMAIN_NAME="${PROJECT_NAME}.dev.ua"
HTTP_PORT="8383"
PROXY_PASS="http://127.0.0.1:${HTTP_PORT}/"