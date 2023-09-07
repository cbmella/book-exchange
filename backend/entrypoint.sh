#!/bin/bash

# Instala dependencias con Composer
composer install

# Start php-fpm in the background
php-fpm &

# Iniciar Nginx en primer plano
nginx -g "daemon off;"
