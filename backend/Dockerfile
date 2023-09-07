FROM php:8.0-fpm

# Instalar extensiones y dependencias
RUN apt-get update && apt-get install -y libpng-dev libonig-dev libxml2-dev zip git curl
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Nginx
RUN apt-get install -y nginx

# Copiar la configuración de Nginx para Laravel
COPY nginx.conf /etc/nginx/sites-available/default

# Copiar el entrypoint.sh y darle permisos de ejecución
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer el puerto 80 para Nginx
EXPOSE 80

# Establecer el entrypoint.sh como el comando por defecto a ejecutar
ENTRYPOINT ["/entrypoint.sh"]
