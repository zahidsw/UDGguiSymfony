FROM alpine:latest

MAINTAINER Vincent Composieux <vincent.composieux@gmail.com>

RUN apk add --update nginx 
RUN apk add php7-sqlite3 
RUN apk add sqlite 
RUN apk add php7-pdo_sqlite
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD nginx.conf /etc/nginx/
ADD symfony.conf /etc/nginx/conf.d/


RUN echo "upstream php-upstream { server php:9001; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
