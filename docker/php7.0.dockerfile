# FROM php:7.0-fpm

# ADD ./php/www.conf /usr/local/etc/php-fpm.d/www.conf

# #RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/bash -D laravel

# RUN mkdir -p /var/www/html

# #ADD ./mysql /var/www/html

# #RUN chmod -R 777 /var/www/html/storage
# #RUN chmod -R 777 /var/www/html/bootstrap/cache

# RUN docker-php-ext-install pdo pdo_mysql iconv mcrypt

# # RUN chown -R laravel:laravel /var/www/html

#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#

FROM php:7.0-fpm

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

#
#--------------------------------------------------------------------------
# Software's Installation
#--------------------------------------------------------------------------
#
# Installing tools and PHP extentions using "apt", "docker-php", "pecl",
#

# Install "curl", "libmemcached-dev", "libpq-dev", "libjpeg-dev",
#         "libpng12-dev", "libfreetype6-dev", "libssl-dev", "libmcrypt-dev",
RUN set -eux; \
    sed -i 's/^deb /# deb /g' /etc/apt/sources.list; \
    echo 'deb http://archive.debian.org/debian/ stretch main contrib non-free' >> /etc/apt/sources.list; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libmcrypt-dev; \
    # cleanup
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    # Install the PHP mcrypt extention
    docker-php-ext-install mcrypt; \
    # Install the PHP pdo_mysql extention
    docker-php-ext-install pdo_mysql; \
    # Install the PHP pdo_pgsql extention
    docker-php-ext-install pdo_pgsql; \
    # Install the PHP gd library
    docker-php-ext-configure gd \
            --enable-gd-native-ttf \
            --with-jpeg-dir=/usr/lib \
            --with-webp-dir=/usr/lib \
            --with-freetype-dir=/usr/include/freetype2; \
    docker-php-ext-install gd; \
    php -r 'var_dump(gd_info());'


    RUN set -xe; \
    apt-get update -yqq && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq \
      apt-utils \
      gnupg2 \
      git \
      #
      #--------------------------------------------------------------------------
      # Mandatory Software's Installation
      #--------------------------------------------------------------------------
      #
      # Mandatory Software's such as ("mcrypt", "pdo_mysql", "libssl-dev", ....)
      # are installed on the base image 'laradock/php-fpm' image. If you want
      # to add more Software's or remove existing one, you need to edit the
      # base image (https://github.com/Laradock/php-fpm).
      #
      # next lines are here becase there is no auto build on dockerhub see https://github.com/laradock/laradock/pull/1903#issuecomment-463142846
      libzip-dev zip unzip && \
      if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
        docker-php-ext-configure zip; \
      else \
        docker-php-ext-configure zip --with-libzip; \
      fi && \
      # Install the zip extension
      docker-php-ext-install zip && \
      php -m | grep -q 'zip'

RUN docker-php-ext-install bcmath

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
   sed -i -e "s/^ *memory_limit.*/memory_limit = 4G/g" /usr/local/etc/php/php.ini && \
   sed -i -e "s/;extension=zip/extension=zip/g" /usr/local/etc/php/php.ini


RUN apt-get update && apt-get -y --no-install-recommends install git \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && rm -rf /var/lib/apt/lists/*

RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 10M;" >> /usr/local/etc/php/conf.d/uploads.ini

RUN composer require phpoffice/phpword --no-interaction --no-progress --no-suggest
