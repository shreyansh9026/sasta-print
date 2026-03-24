# Use official PHP image with Apache
FROM php:8.2-apache

# Install platform dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libicu-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring zip exif pcntl bcmath opcache intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache configuration to point to 'public' directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && (if [ -d /var/www/html/public/uploads ]; then chmod -R 755 /var/www/html/public/uploads; fi)

# Install Composer dependencies (production only)
RUN composer install --no-dev --optimize-autoloader

# Expose port (Render uses 10000 by default but can be configured)
EXPOSE 80

# Environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# Entry point (apache2-foreground is the default in this image)
# Update Apache to listen on $PORT if available, otherwise default to 80
CMD sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground

