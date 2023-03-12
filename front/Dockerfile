FROM caddy:2.6-builder-alpine AS app_caddy_builder
RUN xcaddy build

FROM node:16.19-alpine AS app_node

WORKDIR /srv/app

COPY package*.json ./
RUN npm install

RUN npm i -g @quasar/cli

COPY . .

RUN npm run build:pwa

FROM caddy:2.6-alpine AS app_caddy

WORKDIR /srv/app

COPY --from=app_caddy_builder --link /usr/bin/caddy /usr/bin/caddy
COPY --link docker/caddy/Caddyfile /etc/caddy/Caddyfile
