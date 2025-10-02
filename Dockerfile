# Usa la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Habilitar Apache y servir public/
WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf
RUN echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini
# Composer (opcional si Railway ya lo hace en build)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto 80
EXPOSE 80

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


CMD ["apache2-foreground"]
