#!/bin/bash

# Create SQLite database if it doesn't exist
touch database/database.sqlite

# Run migrations and seed data
php artisan migrate --force
php artisan db:seed --force

# Link storage
php artisan storage:link

# Make sure permissions are correct so Apache can write to db and storage
chown -R www-data:www-data /var/www/html/database
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Start Apache in the foreground
apache2-foreground
