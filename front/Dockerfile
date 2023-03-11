FROM node:16.19-alpine

WORKDIR /srv/front

COPY package*.json ./
RUN npm i -g @quasar/cli
COPY . .
RUN npm install
RUN npm run build:pwa
