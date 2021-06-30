# Base image
FROM php:7.4-fpm-alpine

# Install XML extension dependencies 
RUN apk --update add --no-cache --virtual .php-ext-install-deps \
        $PHPIZE_DEPS \
        # xsl deps
        libxslt-dev \
        libgcrypt-dev
# Install PHP extensions
RUN docker-php-ext-install xsl
