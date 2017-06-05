FROM node:7-onbuild

RUN apt-get update && apt-get -y upgrade \
    apt-get install -y vim

WORKDIR /usr/src/app

RUN npm install react --save \
    npm install react-dom --save \
    npm install redux --save \
    npm install react-redux --save \
    npm install redux-thunk --save \
    npm install react-redux-form@latest --save

EXPOSE 3000

CMD [ "npm", "install" ]
CMD [ "npm", "start" ]