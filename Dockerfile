# Official PHP with Apache
FROM php:8.2-apache

# Install CA certificates and PDO MySQL extension
RUN apt-get update && apt-get install -y \
    ca-certificates \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for custom routing and .htaccess
RUN a2enmod rewrite

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Configure Apache port dynamically to match Render's $PORT environment variable
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Set working directory
WORKDIR /var/www/html

# Copy all project files into the container
COPY . /var/www/html/

# Ensure web server has proper ownership permissions
RUN chown -R www-data:www-data /var/www/html

# Default port fallback (Render injects PORT automatically)
ENV PORT=80

EXPOSE 80

CMD ["apache2-foreground"]
