FROM php:7.2-apache
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && rm -rf /var/lib/apt/lists/* \

ADD conf/php.ini /etc/php/conf.d/
ADD conf/php.ini /etc/php/cli/conf.d/