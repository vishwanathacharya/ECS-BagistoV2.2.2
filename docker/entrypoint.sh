#!/bin/sh

# Wait for database
echo "Waiting for database..."
while ! mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --ssl=0 -e "SELECT 1" >/dev/null 2>&1; do
    sleep 2
done

# Create .env file from environment variables
cat > .env << EOF
APP_NAME=Bagisto
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=${APP_URL:-http://localhost}

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Filesystem configuration for local storage
FILESYSTEM_DISK=public
FILESYSTEM_DRIVER=local
EOF

# Generate app key if not exists
if [ ! -f .env ] || ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Create storage directories
mkdir -p storage/app/public
mkdir -p public/storage

# Always create storage link for images
php artisan storage:link --force

# Create sample images directory structure
mkdir -p storage/app/public/product
mkdir -p storage/app/public/category
mkdir -p storage/app/public/slider
mkdir -p storage/app/public/logo

# Check if database is initialized
TABLES=$(mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --ssl=0 "$DB_DATABASE" -e "SHOW TABLES;" 2>/dev/null | wc -l)

if [ "$TABLES" -lt 5 ]; then
    echo "Initializing database..."
    
    # Run migrations
    php artisan migrate --force
    
    # Add missing columns if needed
    mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --ssl=0 "$DB_DATABASE" -e "
    ALTER TABLE visits ADD COLUMN IF NOT EXISTS channel_id INT UNSIGNED NULL;
    ALTER TABLE theme_customizations ADD COLUMN IF NOT EXISTS theme_code VARCHAR(191) NULL DEFAULT 'default';
    " 2>/dev/null || true
    
    # Seed database
    php artisan db:seed --force
    
    echo "Database initialized successfully!"
else
    echo "Database already initialized, running migrations only..."
    php artisan migrate --force
fi

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Set permissions for storage and public directories
chown -R www-data:www-data storage bootstrap/cache public/storage
chmod -R 755 storage bootstrap/cache public/storage

# Ensure storage link exists and is accessible
ls -la public/storage || echo "Storage link creation failed"

exec "$@"
