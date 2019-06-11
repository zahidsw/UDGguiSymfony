FROM alpine:3.8

LABEL maintainer="Vincent Composieux <vincent.composieux@gmail.com>"

RUN apk add --update \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xdebug \
    php7-zip \
    make \
    python \
    py-pip \
    git \
    curl
RUN mkdir -p /home/mandint/slice-manager/
RUN mkdir -p /home/mandint/tmp/
RUN git clone https://zmuhammad:QXnHszaA-HVPwiUn2Ryu@gitlab.distantaccess.com/mandint/slice-manager.git /home/mandint/slice-manager
RUN pip install -r /home/mandint/slice-manager/requirements.txt --no-deps
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/* && \
    curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

ADD symfony.ini /etc/php7/conf.d/
ADD symfony.ini /etc/php7/cli/conf.d/
ADD xdebug.ini  /etc/php7/conf.d/

ADD symfony.pool.conf /etc/php7/php-fpm.d/

CMD ["php-fpm7", "-F"]

WORKDIR /var/www/symfony
EXPOSE 9001
