FROM caddy:2.6-builder-alpine AS app_caddy_builder
RUN xcaddy build

FROM node:16.19-alpine AS app_node

WORKDIR /srv/front

COPY package*.json ./
RUN npm install

RUN npm i -g @quasar/cli

COPY . .

RUN npm run build:pwa

FROM caddy:2.6-alpine AS app_caddy

WORKDIR /srv/front

COPY --from=app_caddy_builder --link /usr/bin/caddy /usr/bin/caddy
COPY --link docker/caddy/Caddyfile /etc/caddy/Caddyfile
