FROM php:7.4-apache

# Set working directory
WORKDIR /var/www/html/cms

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html/cms

# Copy Apache configuration file
COPY docker/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the Apache rewrite module
RUN a2enmod rewrite

# Set proper permissions for the application files
RUN chown -R www-data:www-data /var/www/html/cms

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
