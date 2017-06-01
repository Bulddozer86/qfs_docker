FROM ubuntu:16.04

RUN apt-get update && apt-get -y upgrade
RUN apt-get install -y python-software-properties
RUN apt-get install -y curl
RUN curl -sL https://deb.nodesource.com/setup_7.x | bash -
RUN apt-get update
RUN apt-get install -y nodejs
#
#RUN update-alternatives --install /usr/bin/node nodejs /usr/bin/nodejs 100
#RUN curl https://www.npmjs.com/install.sh | sh
RUN mkdir -p /usr/src/app

WORKDIR /usr/src/app

COPY ./app/* /usr/src/app/

RUN npm install

EXPOSE 3000

CMD [ "npm", "start" ]