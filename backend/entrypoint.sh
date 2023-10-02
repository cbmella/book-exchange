#!/bin/bash

# Instala dependencias con Composer
composer install

# Si el archivo .env no existe, copia el .env.example y genera la clave de aplicación
echo "Verificando la existencia de .env..."
if [ ! -f "/var/www/html/.env" ]; then
    echo ".env no encontrado, copiando de .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
    php /var/www/html/artisan key:generate
else
    echo ".env ya existe."
fi


# Establecer permisos para los directorios de Laravel
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start php-fpm in the background
php-fpm &

# Iniciar Nginx en primer plano
nginx -g "daemon off;"
