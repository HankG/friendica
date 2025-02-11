ARG VARIANT="8.2-apache-bullseye"
FROM mcr.microsoft.com/vscode/devcontainers/php:${VARIANT}

ARG DEBIAN_FRONTEND=noninteractive
ARG apcu_version=5.1.23
ARG memcached_version=3.2.0
ARG redis_version=6.0.2
ARG imagick_version=3.7.0

RUN apt-get update -y;

# Install MariaDB client
RUN apt-get install -y mariadb-client

# Base packages
RUN apt install -y vim software-properties-common sudo nano gnupg2

# entrypoint.sh and cron.sh dependencies
RUN apt-get install -y --no-install-recommends \
        rsync \
        bzip2 \
        msmtp \
        tini

RUN apt-get install -y --no-install-recommends \
        bash \
        libpng-dev \
        libjpeg62-turbo-dev \
        libtool \
        libmagick++-dev \
        libmemcached-dev \
        zlib1g-dev \
        libssl-dev \
        libgraphicsmagick1-dev \
        libfreetype6-dev \
        libwebp-dev \
        librsvg2-2 \
        libzip-dev \
        libldap2-dev \
        libgmp-dev \
        libmagickcore-6.q16-6-extra \
        ; \
        \
	docker-php-ext-configure gd \
            --with-freetype \
            --with-jpeg \
            --with-webp \
        ; \
 	docker-php-ext-install -j "$(nproc)" \
            pdo_mysql \
            gd \
            exif \
            zip \
            opcache \
            ctype \
            pcntl \
            ldap \
            gmp \
            intl

# pecl will claim success even if one install fails, so we need to perform each install separately
RUN pecl install apcu-${apcu_version}; \
        pecl install memcached-${memcached_version}; \
        pecl install redis-${redis_version}; \
        pecl install imagick-${imagick_version}; \
        docker-php-ext-enable \
            apcu \
            memcached \
            redis \
            imagick

RUN apt-get clean -y && rm -rf /var/lib/apt/lists/*

ENV PHP_MEMORY_LIMIT 512M
ENV PHP_UPLOAD_LIMIT 512M

RUN { \
        echo 'opcache.enable=1' ; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.save_comments=1'; \
        echo 'opcache.revalidte_freq=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini; \
    \
    echo 'apc.enable_cli=1' >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini; \
    \
    { \
        echo 'memory_limit=${PHP_MEMORY_LIMIT}'; \
        echo 'upload_max_filesize=${PHP_UPLOAD_LIMIT}'; \
        echo 'post_max_size=${PHP_UPLOAD_LIMIT}'; \
    } > /usr/local/etc/php/conf.d/friendica.ini; \
    ln -s /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini; \
    \
    mkdir /var/www/data; \
    chmod -R g=u /var/www
