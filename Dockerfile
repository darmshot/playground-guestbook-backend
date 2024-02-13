FROM composer AS composer

# copying the source directory and install the dependencies with composer
COPY ./ /app

# run composer install to install the dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress

# continue stage build with the desired image and copy the source including the
# dependencies downloaded by composer
FROM erseco/alpine-php-webserver

USER root

RUN apk --no-cache add php82-pdo \
        php82-pdo_mysql

# Switch to use a non-root user from here on
USER nobody

COPY --chown=nobody --from=composer /app /var/www/html

VOLUME /var/www/html/storage

COPY --chown=nobody /docker/nginx/nginx.conf /etc/nginx/nginx.conf

