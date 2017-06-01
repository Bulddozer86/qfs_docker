FROM php:7.1-fpm

RUN apt-get update \
    apt-get install -y vim \
    apt-get install -y autoconf g++ make openssl libssl-dev libcurl4-openssl-dev pkg-config libsasl2-dev libpcre3-dev \
    pecl install mongodb \
    pecl install xdebug-beta \
	  docker-php-ext-enable xdebug

RUN echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongodb.ini
