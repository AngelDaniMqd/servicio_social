# Usa la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala las extensiones necesarias para Laravel y MySQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Habilita mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copia el código de la app al contenedor
COPY . /var/www/html

# Cambia los permisos de storage y bootstrap/cache (necesario para Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Cambia el DocumentRoot de Apache a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instala dependencias de PHP
WORKDIR /var/www/html
RUN composer install --optimize-autoloader --no-dev

# Expone el puerto 80
EXPOSE 80

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


CMD ["apache2-foreground"]
