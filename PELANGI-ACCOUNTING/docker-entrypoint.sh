#!/bin/bash

# Wait for database to be ready
echo "Waiting for database to be ready..."
while ! php -r "\$fp = @fsockopen('${DB_HOST:-postgres}', ${DB_PORT:-5432}); if(\$fp) { fclose(\$fp); exit(0); } exit(1);"; do
    sleep 1
done
echo "Database is ready!"

# Check if composer dependencies are installed
if [ ! -d "vendor" ]; then
    echo "Installing composer dependencies..."
    composer install --optimize-autoloader --no-interaction
fi

# Check if node modules are installed
if [ ! -d "node_modules" ]; then
    echo "Installing node modules..."
    npm install
fi

# Check if APP_KEY is set
if grep -q "APP_KEY=base64:" .env; then
    echo "APP_KEY already set"
else
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Only run initialization if explicitly requested via environment variable
if [ "${DOCKER_INIT:-false}" = "true" ]; then
    echo "Running Docker initialization..."

    # Run migrations
    echo "Running migrations..."
    php artisan migrate --force

    # Cache configuration and routes for better performance
    echo "Caching configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    echo "Docker initialization completed!"
fi

# Set proper permissions as root
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure app files are readable by www-data but keep host ownership
# This allows you to edit files from your host machine
if [ -d "/var/www/html/app" ]; then
    echo "Setting app file permissions..."
    # Make files readable by all (allows www-data to read, host user to edit)
    find /var/www/html/app -type f -exec chmod 664 {} \;
    find /var/www/html/app -type d -exec chmod 775 {} \;
fi

# Ensure log file exists with proper permissions
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 664 /var/www/html/storage/logs/laravel.log

# Execute php-fpm (it will run as configured in php-fpm.conf)
# Execute the command passed to the container
exec "$@"