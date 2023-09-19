Mis disculpas por la omisión. Vamos a detallar el contenido de los archivos Docker y cómo configurarlos.

### 1. Configuración inicial y entorno de desarrollo

1. **Iniciar una nueva feature en GitFlow**:
```bash
git flow feature start setup-environment
```

2. **Configuración de Docker**:

   - **Dockerfile-backend**:
     Este archivo define el entorno para Laravel y PHP.
     ```Dockerfile
     FROM php:7.4-fpm

     # Instalar extensiones necesarias
     RUN docker-php-ext-install pdo pdo_mysql

     # Instalar Composer
     COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

     WORKDIR /var/www

     CMD ["php-fpm"]
     ```

   - **Dockerfile-frontend**:
     Este archivo define el entorno para Node y Vue.js.
     ```Dockerfile
     FROM node:14

     WORKDIR /app

     CMD ["npm", "run", "serve"]
     ```

   - **docker-compose.yml**:
     Define los servicios, volúmenes y redes.
     ```yaml
     version: '3'

     services:
       backend:
         build:
           context: .
           dockerfile: Dockerfile-backend
         volumes:
           - ./backend:/var/www
         depends_on:
           - db

       frontend:
         build:
           context: .
           dockerfile: Dockerfile-frontend
         volumes:
           - ./frontend:/app
         ports:
           - "8080:8080"

       db:
         image: mysql:5.7
         environment:
           MYSQL_DATABASE: my_database
           MYSQL_USER: user
           MYSQL_PASSWORD: password
           MYSQL_ROOT_PASSWORD: root_password
         volumes:
           - db_data:/var/lib/mysql

       webserver:
         image: nginx:alpine
         volumes:
           - ./backend:/var/www
           - ./nginx.conf:/etc/nginx/nginx.conf
         ports:
           - "80:80"
         depends_on:
           - backend

     volumes:
       db_data: {}
     ```

   - **nginx.conf**:
     Configuración básica para servir Laravel usando Nginx.
     ```nginx
     server {
         listen 80;
         index index.php index.html;
         root /var/www/public;
         location / {
             try_files $uri /index.php?$args;
         }
         location ~ \.php$ {
             fastcgi_split_path_info ^(.+\.php)(/.+)$;
             fastcgi_pass backend:9000;
             fastcgi_index index.php;
             include fastcgi_params;
             fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
             fastcgi_param PATH_INFO $fastcgi_path_info;
         }
     }
     ```

   - Construye las imágenes de Docker y levanta los contenedores.
     ```bash
     docker-compose build
     docker-compose up -d
     ```

3. **Commit**:
```bash
git add Dockerfile-backend Dockerfile-frontend docker-compose.yml nginx.conf
git commit -m "feat(setup): add Docker configuration files and setup environment"
```

4. **Configuración de Laravel**:
   - Navega al contenedor de Docker para Laravel.
```bash
docker exec -it [nombre_del_contenedor_backend] bash
```
   - Instala Laravel.
```bash
composer create-project --prefer-dist laravel/laravel .
```
   - Configura el archivo `.env` con las credenciales de la base de datos y cualquier otra configuración necesaria.
   - Genera la clave de la aplicación.
```bash
php artisan key:generate
```

5. **Commit**:
```bash
git add .env
git commit -m "feat(setup): install and configure Laravel"
```

6. **Finalizar la feature en GitFlow**:
```bash
git flow feature finish setup-environment
```