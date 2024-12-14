FROM php:8.2-cli

# Install Redis extension
RUN apt-get update && \
    pecl install redis && \
    docker-php-ext-enable redis

# Set the working directory
WORKDIR /var/www/html

# Expose port 8080
EXPOSE 8080

# Start the PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]