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
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

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
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
# Ensure prefork is enabled (symlink if missing)
if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.load ]; then
    ln -s ../mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
    ln -s ../mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
fi

exec apache2-foreground
