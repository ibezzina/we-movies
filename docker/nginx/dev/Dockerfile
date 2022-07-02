FROM nginx:1.21-alpine

ARG PHPFPM_SERVICE
ARG CORS_HEADER

COPY ./docker/nginx/dev/etc/conf.d/default.conf /tmp/default.conf
RUN /bin/sh -c "envsubst '\$PHPFPM_SERVICE,\$CORS_HEADER' < /tmp/default.conf > /etc/nginx/conf.d/default.conf"

WORKDIR /usr/local/src/app

COPY ./public /usr/local/src/app/public
