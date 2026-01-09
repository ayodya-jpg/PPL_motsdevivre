FROM php:8.2-apache

# 1. Install dependencies sistem & PHP extensions
# Menambahkan git, unzip, dan zip (wajib ada agar Composer bisa mendownload paket)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    git \
    unzip \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. AMBIL BINARY COMPOSER (BAGIAN YANG HILANG)
# Mengambil file eksekusi composer dari image resmi composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Enable Apache modules
RUN a2enmod rewrite

# 4. Set Working Directory
WORKDIR /var/www/html

# 5. Copy application files
COPY . /var/www/html

# 6. Jalankan Composer Install
ENV COMPOSER_PROCESS_TIMEOUT=2000
RUN composer install --no-interaction --optimize-autoloader

# 7. Set permissions
# Khusus CodeIgniter 4, folder 'writable' harus memiliki izin tulis
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

# 8. Configure Apache DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]