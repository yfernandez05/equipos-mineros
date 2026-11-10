#!/bin/sh
set -e

cd /var/www/html

# Verificar que APP_KEY este configurada
if [ -z "$APP_KEY" ]; then
    echo "ERROR: La variable APP_KEY no está configurada."
    exit 1
fi

# Preparar permisos
chown -R www-data:www-data storage bootstrap/cache

# Ejecutar migraciones
php artisan migrate --force

# Iniciar Apache
exec apache2-foreground
