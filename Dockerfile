FROM php:7.4.0-fpm-alpine

RUN apk update && apk add --no-cache supervisor nginx libzip-dev

RUN docker-php-ext-install zip tokenizer mysqli pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer global require "laravel/lumen-installer"
ENV PATH $PATH:/tmp/vendor/bin

COPY ./cronjobs /etc/crontabs/root

ADD supervisord.conf /etc/

COPY ./nginx.conf /etc/nginx/conf.d/default.conf

COPY ./lumen/ /var/www/html/

WORKDIR /var/www/html/

RUN composer update

RUN mkdir -p /var/www/html/storage/logs
RUN chmod -R o+w /var/www/html/storage

CMD ["supervisord", "-c", "/etc/supervisord.conf"]