ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-fpm-bookworm

ARG XDEBUG_VERSION

# Install system dependencies
RUN apt-get -y update && apt-get -y upgrade && apt-get -y install \
    apt-utils \
    sudo \
    nano \
    build-essential \
    git \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    libbz2-dev \
    locales \
    libmcrypt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install exif \
        pcntl \
        bcmath \
        ctype \
        curl \
        pdo_mysql \
        xml \
        zip

# Install XDebug
RUN curl -fsSL https://github.com/xdebug/xdebug/archive/refs/tags/$XDEBUG_VERSION.tar.gz -o xdebug.tar.gz \
    && mkdir -p /tmp/xdebug \
    && tar -xf xdebug.tar.gz -C /tmp/xdebug --strip-components=1 \
    && rm xdebug.tar.gz \
    && docker-php-ext-configure /tmp/xdebug --enable-xdebug \
    && docker-php-ext-install /tmp/xdebug \
    && rm -r /tmp/xdebug
RUN \
    echo "xdebug.start_with_request = \${XDEBUG_START_WITH_REQUEST}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.discover_client_host = \${XDEBUG_DISCOVER_CLIENT_HOST}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Cleanup
RUN \
    apt-get clean && \
    apt-get autoremove -y && \
    rm -rf /build /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer:2.8.4 /usr/bin/composer /usr/bin/composer

ARG LOCAL_UID=1000
ARG LOCAL_GID=1000
RUN test "$LOCAL_UID" -gt 0 && test "$LOCAL_GID" -gt 0 && \
    groupmod -o -g "$LOCAL_GID" www-data && \
    usermod -o -u "$LOCAL_UID" -g www-data www-data

ENV HOME=/var/www \
    COMPOSER_HOME=/var/www/.composer
RUN mkdir -p /var/www/.composer && \
    chown -R www-data:www-data /var/www/.composer

WORKDIR /var/www/html
USER www-data
