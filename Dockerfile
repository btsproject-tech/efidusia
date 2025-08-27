# Gunakan PHP 7.4 dengan Apache
FROM php:7.4-apache

# Install dependencies dan ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Aktifkan mod_rewrite untuk Laravel
RUN a2enmod rewrite

# Set document root ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Ubah konfigurasi Apache agar document root ke public/
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Salin source code Laravel ke dalam container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Beri permission yang aman
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# (Opsional) Install dependency Laravel saat build
# RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
