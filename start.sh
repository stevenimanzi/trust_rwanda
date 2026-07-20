#!/bin/bash

# Create required storage directories if missing
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/logs

# Create SQLite database if it doesn't exist
touch database/database.sqlite

# Set permissions BEFORE running artisan commands
chown -R www-data:www-data /var/www/html/database
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chmod 664 /var/www/html/database/database.sqlite

# Clear any stale caches from build
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations and seed data
php artisan migrate --force
php artisan db:seed --force

# Link storage
php artisan storage:link 2>/dev/null || true

# Start Apache in the foreground
apache2-foreground
