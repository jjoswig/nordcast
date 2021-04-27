FROM php:8.0-apache
RUN apt update && apt -y install git unzip libssl-dev pkg-config
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --quiet
RUN php composer.phar require mongodb/mongodb microsoft/azure-storage-blob microsoft/azure-storage-table microsoft/azure-storage-queue microsoft/azure-storage-file
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN sed -ri -e 's!upload_max_filesize = 2M!upload_max_filesize = 100M!g' $PHP_INI_DIR/php.ini
RUN sed -ri -e 's!post_max_size = 8M!post_max_size = 100M!g' $PHP_INI_DIR/php.ini


COPY index.php /var/www/html