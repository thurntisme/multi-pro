FROM php:8.1-fpm

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Optional: Enable other extensions if needed
RUN docker-php-ext-enable mysqli pdo pdo_mysql
