#!/usr/bin/env bash

source ./bash/variables.sh

declare -A ARRAY_CUSTOM_DOCKER_IMAGES
ARRAY_CUSTOM_DOCKER_IMAGES=([$IMAGE_NGINX]='nginx' [$IMAGE_PHP]='php' [$IMAGE_ELASTICSEARCH]='elasticsearch' [$IMAGE_NODE]='node')
ARRAY_DOCKER_NAMES=($D_MONGO $D_ELASTICSEARCH $D_FRONTEND $D_SERVER $D_PHP)

function error_msg {
  echo -e "\n${TEXT_COLOR}\e[41m \n $1 \n \e[49m\n \e[0m"
}

function notice_msg {
  echo -e "\n${TEXT_COLOR}\e[43m \n $1 \n \e[49m\n \e[0m"
}

function success_msg {
  echo -e "\n${TEXT_COLOR}\e[42m \n $1 \n \e[49m\n \e[0m"
}

function info_msg {
    echo -e "\n\e[44m\n${TEXT_COLOR}\e[44m$1\n\e[0m"
}


spinner() {
    i=1
    sp="/-\|"
    while [ -d /proc/$! ]
    do
        printf "\r\r"
        printf "${TEXT_COLOR}\e[42m \b${sp:i++%${#sp}:1} \r\e[49m\e[0m"
        printf "\r\r"
        sleep 0.1
    done
}

function default_msg {
    echo -e "\e[0m $1 \e[49m \e[0m"
}

function stop_run_old_dockers {
    for i in ${ARRAY_DOCKER_NAMES[@]}; do
        grepr=$(docker ps | grep ${i})
        grepr=$?

        if [[ $grepr -eq 0 ]]; then
            default_msg "$(docker stop ${i}) stop"
        fi
    done
}

function start_all_required_dockers {
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
}

IMAGES=(${!ARRAY_CUSTOM_DOCKER_IMAGES[@]})

info_msg 'Check if docker images exist';

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
        default_msg "Images '${IMAGE}' builded already!"
    fi
done

info_msg "Stop dockers if exist already"
stop_run_old_dockers & spinner $!
info_msg "Run dockers please waiting ... "
docker-compose up -d --force-recreate
start_all_required_dockers & spinner $!

sleep 5

docker exec -t -i $D_PHP php bin/console parser:run
docker exec -t -i $D_PHP php bin/console cleaner:run
docker exec -t -i $D_PHP php bin/console flat:download

APACHE_CONF="
<VirtualHost *:80>
  ServerName ${DOMAIN_NAME}

  ProxyPreserveHost On
  ProxyPass / ${PROXY_PASS}
  ProxyPassReverse / ${PROXY_PASS}

  ErrorLog /var/log/apache2/${PROJECT_NAME}.error.log

  LogLevel debug

  CustomLog /var/log/apache2/${PROJECT_NAME}.log combined
</VirtualHost>
"

echo ">> Add local environment"

sudo a2enmod proxy_http && a2enmod headers && a2enmod rewrite
echo "${APACHE_CONF}" > /etc/apache2/sites-enabled/${PROJECT_NAME}.conf

cat /etc/hosts | grep ${PROJECT_NAME}
greprc=$?

if [[ ! $greprc -eq 0 ]] ;
then
  echo "127.0.0.1 ${DOMAIN_NAME}" >> /etc/hosts
fi;

sudo service apache2 restart

#php bin/console fos:elastica:populate
#docker exec -it nginx-server nginx -s reload
