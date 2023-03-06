FROM caddy:2.6-builder-alpine AS app_caddy_builder
RUN xcaddy build

FROM node:16.19-alpine as app_node
ENV APP_ENV=prod
WORKDIR /srv/app
COPY package*.json ./
RUN npm i -g @quasar/cli
COPY . .

FROM app_node as app_node_build
RUN npm install
RUN npm run build:pwa

FROM caddy:2.6-alpine AS app_caddy

WORKDIR /srv/app

COPY --from=app_caddy_builder --link /usr/bin/caddy /usr/bin/caddy
COPY --from=app_node_build --link /srv/app/dist/pwa public/
COPY --link docker/caddy/Caddyfile /etc/caddy/Caddyfile
