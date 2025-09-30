FROM php:8.3.9-apache

<<<<<<< HEAD
# Declare composer version
=======
# Composer version
>>>>>>> lab3-redis_localstogare
ENV COMPOSER_VERSION=2.7.7

RUN apt-get update \
    && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    supervisor \
    libpng-dev \
    libpq-dev \
    libzip-dev \
    libexif-dev \
    gnupg \
    tzdata \
<<<<<<< HEAD
=======
    pkg-config \
    libssl-dev \
>>>>>>> lab3-redis_localstogare
    && docker-php-ext-install \
    bcmath \
    gd \
    opcache \
    sockets \
    zip \
    exif \
    pdo_mysql \
    mysqli \
    && docker-php-ext-enable pdo_mysql

<<<<<<< HEAD
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

ENV APACHE_DOCUMENT_ROOT /var/www/html

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Install Composer with specified version
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=$COMPOSER_VERSION

RUN a2enmod rewrite

RUN useradd -ms /bin/bash -u 1000 app

#COPY start /usr/local/bin/

#CMD [ "start" ]
=======
# 🔹 Cài Xdebug (nếu cần)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# 🔹 Cài Redis thủ công từ source (phpredis)
RUN docker-php-source extract \
    && git clone --branch 6.0.2 https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
    && docker-php-ext-install redis \
    && docker-php-source delete

# Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer \
    --version=$COMPOSER_VERSION

# Apache rewrite
RUN a2enmod rewrite

# Tạo user
RUN useradd -ms /bin/bash -u 1000 app
>>>>>>> lab3-redis_localstogare
