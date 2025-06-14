FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql


# Enable mod_rewrite
RUN a2enmod rewrite
# Enable AllowOverride
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
# Restart Apache to apply edits
RUN service apache2 restart

# Copy .htaccess and code
COPY .htaccess /var/www/html/.htaccess

WORKDIR /var/www/html
