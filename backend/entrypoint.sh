#!/bin/bash

# Instala dependencias con Composer
composer install

# Si el archivo .env no existe, copia el .env.example y genera la clave de aplicación
if [ ! -f "/var/www/html/.env" ]; then
    cp /var/www/html/.env.example /var/www/html/.env
    php /var/www/html/artisan key:generate
fi

# Establecer permisos para los directorios de Laravel
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start php-fpm in the background
php-fpm &

# Iniciar Nginx en primer plano
nginx -g "daemon off;"
