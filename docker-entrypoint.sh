#!/bin/bash
set -e

# Ensure storage directories exist (volume mounts can be empty on first boot)
mkdir -p storage/framework/cache storage/framework/views storage/framework/sessions storage/framework/testing storage/app/public bootstrap/cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force
echo "Running seeders..."
php artisan db:seed --force || echo "Seeding failed, but continuing deployment."
php artisan storage:link || true

# Fix permissions for SQLite and storage
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear and cache config
echo "Optimizing application..."
php artisan optimize
php artisan view:cache
php artisan event:cache || true

# Start Apache
echo "Starting Apache..."

echo "PORT environment variable is: '${PORT}'"

# Configure Apache port
TARGET_PORT=${PORT:-80}
echo "Configuring Apache to listen on 0.0.0.0:$TARGET_PORT..."

# Update ports.conf to listen on 0.0.0.0
sed -i "s/Listen 80/Listen 0.0.0.0:$TARGET_PORT/g" /etc/apache2/ports.conf
# Also update the default vhost
sed -i "s/:80/:$TARGET_PORT/g" /etc/apache2/sites-enabled/000-default.conf

echo "--- ports.conf content ---"
cat /etc/apache2/ports.conf
echo "--------------------------"

echo "Fixing MPM conflicts..."
a2dismod mpm_event mpm_worker || true
a2enmod mpm_prefork || true

exec apache2-foreground
