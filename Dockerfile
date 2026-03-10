FROM php:8.4-apache

# 1. Install system dependencies for PHP and PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libpq-dev

# 2. Install Node.js (needed for Vite and Tailwind)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 3. Install PHP extensions (This is where pdo_pgsql is installed!)
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# 4. Enable Apache mod_rewrite (needed for Laravel routing)
RUN a2enmod rewrite

# 5. Change Apache DocumentRoot to Laravel's /public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Set working directory and copy files
WORKDIR /var/www/html
COPY . .

# 8. Install PHP dependencies
RUN composer install --optimize-autoloader

# 9. Install Node dependencies and build CSS/JS
RUN npm install && npm run build

# 10. Set folder permissions so Laravel can write logs/caches
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80