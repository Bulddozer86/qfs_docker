FROM node:7-onbuild

RUN apt-get update && apt-get -y upgrade
RUN apt-get instal -i vim
#RUN apt-get install -y python-software-properties
#RUN apt-get install -y curl
#RUN curl -sL https://deb.nodesource.com/setup_7.x | bash -
#RUN apt-get update
#RUN apt-get install -y nodejs
#
#RUN mkdir -p /var/www/ && mkdir -p /var/www/app

WORKDIR /usr/src/app

COPY ./app /usr/src/app

RUN npm install react --save
RUN npm install react-dom --save
RUN npm install redux --save
RUN npm install react-redux --save
RUN npm install redux-thunk --save
RUN npm install react-redux-form@latest --save
RUN npm update -g minimatch@3.0.2
#RUN npm install

EXPOSE 3000

CMD [ "npm", "install" ]
CMD [ "npm", "start" ]