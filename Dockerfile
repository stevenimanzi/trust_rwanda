FROM php:8.2-apache

# Install required packages and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache: enable AllowOverride for .htaccess BEFORE changing DocumentRoot
RUN printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --optimize-autoloader

# Set up environment file
RUN cp .env.example .env
RUN php artisan key:generate
RUN sed -i 's/APP_ENV=local/APP_ENV=production/g' .env
RUN sed -i 's|APP_URL=http://localhost|APP_URL=https://trustrwanda.onrender.com|g' .env
RUN sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=cookie/g' .env
RUN sed -i 's/CACHE_STORE=database/CACHE_STORE=file/g' .env
RUN sed -i 's/QUEUE_CONNECTION=database/QUEUE_CONNECTION=sync/g' .env
RUN echo "" >> .env
RUN echo "SESSION_SECURE_COOKIE=true" >> .env
RUN echo "SESSION_DOMAIN=trustrwanda.onrender.com" >> .env

# Change DocumentRoot to Laravel's public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Make Apache listen on the port provided by Render
RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf
RUN sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Add start script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Start the application
CMD ["/usr/local/bin/start.sh"]
