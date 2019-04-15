FROM php:7.2-fpm-alpine

RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql

RUN apk update && apk add --no-cache supervisor nginx

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer global require "laravel/lumen-installer"
ENV PATH $PATH:/tmp/vendor/bin

COPY ./cronjobs /etc/crontabs/root

ADD supervisord.conf /etc/

COPY ./nginx.conf /etc/nginx/conf.d/default.conf

COPY ./lumen/ /var/www/html/

RUN composer update

RUN mkdir /var/www/html/storage
RUN mkdir /var/www/html/storage/logs

CMD ["supervisord", "-c", "/etc/supervisord.conf"]