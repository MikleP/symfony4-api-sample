FROM php:7.2-fpm

# Install recommended extensions for Symfony
RUN apt-get update && apt-get install -y \  
        libicu-dev \
    && docker-php-ext-install \
        intl \
        opcache \
        pdo_mysql \
    && docker-php-ext-enable \
        intl \
        opcache \
        pdo_mysql

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.max_nesting_level=1000" >> /usr/local/etc/php/conf.d/xdebug.ini

# Permission fix
RUN usermod -u 1000 www-data
