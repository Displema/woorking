FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install curl and unzip, needed for composer
RUN apt-get update && apt-get install -y curl unzip

# Install composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable mod_rewrite
RUN a2enmod rewrite
# Enable AllowOverride
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
# Restart Apache to apply edits
RUN service apache2 restart

# Copy .htaccess and code
COPY .htaccess /var/www/html/.htaccess

WORKDIR /var/www/html
