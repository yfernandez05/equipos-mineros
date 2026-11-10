
#!/bin/sh
set -e

cd /var/www/html

# Generar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Preparar permisos
chown -R www-data:www-data storage bootstrap/cache

# Ejecutar migraciones
php artisan migrate --force

# Iniciar Apache
exec apache2-foreground
