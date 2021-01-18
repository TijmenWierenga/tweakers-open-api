#syntax=docker/dockerfile:experimental

FROM composer:2 as composer

FROM php:8-alpine

LABEL maintainer="tijmen.wierenga@persgroep.net"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN  install-php-extensions zip pdo_mysql

# Copy the composer binary to the container
COPY --from=composer /usr/bin/composer /usr/bin/composer
# Set composer home directory
ENV COMPOSER_HOME=/.composer
# Composer needs to run as root to allow the use of a bind-mounted cache volume
ENV COMPOSER_ALLOW_SUPERUSER=1

# Create the project root folder and assign ownership to the pre-existing www-data user
RUN mkdir -p /var/www/html /.composer && chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

# Copy just the composer dependencies to the container. This should lead to a more efficient
# build cache since the 'composer install' cache-layer should only break if one of these two
# files has changed.
COPY --chown=www-data composer.json composer.lock /var/www/html/

# Install all composer dependencies without running the autoloader and the scripts since these
# actions rely on the source files of the application.
# Also, volume mounting a bind-mounted cache to composer's /.composer folder helps speeding up the build
# since even when you break the cache by adding/removing a composer package, all previously installed
# packages are served from the mounted cache.
RUN --mount=type=cache,target=/.composer/cache composer install --no-autoloader --no-scripts --no-dev

# Copy the rest of the source code to the container. Now, if source files are changed, the cache-layer
# breaks here and the only the 'composer dump-autoload' command will have to run again.
COPY --chown=www-data . /var/www/html/

# Generate an optimized autoloader after copying the source files to the container
RUN composer dump-autoload --optimize

# Change ownership of the root folder to www-data
RUN chown -R www-data:www-data vendor/
