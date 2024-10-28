FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zip unzip git \
    libicu-dev pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install intl

ENV PHP_DISPLAY_ERRORS=0 PHP_ERROR_REPORTING=0

RUN echo "display_errors=${PHP_DISPLAY_ERRORS}" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "error_reporting=${PHP_ERROR_REPORTING}" >> /usr/local/etc/php/conf.d/custom.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./app/ /var/www/html/

RUN composer install --no-cache --verbose

RUN chown -R www-data:www-data /var/www/html

COPY flag.txt /flag.txt
RUN chmod 444 /flag.txt

EXPOSE 80

USER nobody

CMD ["apache2-foreground"]
