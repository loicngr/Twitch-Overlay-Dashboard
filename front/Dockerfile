FROM nginx:1.23-alpine

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
COPY dist/pwa /usr/share/nginx/html
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
