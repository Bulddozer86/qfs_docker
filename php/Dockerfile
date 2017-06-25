FROM php:7.1-fpm
#apt-get install software-properties-common
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get update \
    && apt-get install -y vim \
    && apt-get install -y autoconf g++ make openssl libssl-dev libcurl4-openssl-dev pkg-config libsasl2-dev libpcre3-dev \
    && pecl install mongodb \
    && pecl install xdebug-beta \
	&& docker-php-ext-enable xdebug \
	&& apt-get install -y zip unzip

#	&& apt-get install php-imagick
#RUN apt-get install pkg-config libmagickwand-dev -y
#RUN cd /tmp
#RUN wget https://pecl.php.net/get/imagick-3.4.0.tgz
#RUN tar xvzf imagick-3.4.0.tgz
#RUN cd imagick-3.4.0
#RUN phpize
#RUN ./configure
#RUN make install
#RUN rm -rf /tmp/imagick-3.4.0*
#RUN echo extension=imagick.so >> /etc/php/7.1/cli/php.ini
#RUN echo extension=imagick.so >> /etc/php/7.1/fpm/php.ini

RUN echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongodb.ini
