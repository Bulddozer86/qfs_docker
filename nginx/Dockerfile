FROM ubuntu

RUN apt-get update \
    && apt-get install -y vim wget dialog net-tools nginx \
    && rm -v /etc/nginx/nginx.conf

ADD nginx.conf /etc/nginx/
ADD qfs.conf /etc/nginx/sites-available/

RUN echo "daemon off;" >> /etc/nginx/nginx.conf \
    && ln -s /etc/nginx/sites-available/qfs.conf /etc/nginx/sites-enabled/

EXPOSE 80

CMD service nginx start