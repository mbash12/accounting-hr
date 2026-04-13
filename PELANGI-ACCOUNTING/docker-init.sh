#!/bin/bash

echo "Starting Docker initialization..."

# Wait for database to be ready
echo "Waiting for database to be ready..."
while ! php -r "\$fp = @fsockopen('${DB_HOST:-postgres}', ${DB_PORT:-5432}); if(\$fp) { fclose(\$fp); exit(0); } exit(1);"; do
    sleep 1
done
echo "Database is ready!"

# Reset PostgreSQL sequences BEFORE migrations to ensure migrations table sequence is correct
echo "Resetting PostgreSQL sequences..."
php artisan db:reset-pk-sequences

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Optionally seed the database
if [ "${DOCKER_SEED:-false}" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

# Cache configuration and routes for better performance
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear any existing caches
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure log file exists with proper permissions
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 664 /var/www/html/storage/logs/laravel.log

echo "Docker initialization completed successfully!"