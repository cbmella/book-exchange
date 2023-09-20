# Backend del Sistema de Intercambio de Libros

El backend del Sistema de Intercambio de Libros actúa como el núcleo central de la plataforma, proporcionando una API robusta y eficiente para gestionar todas las operaciones relacionadas con los libros, intercambios, opiniones y usuarios. Diseñado con Laravel, uno de los frameworks PHP más populares y confiables, este backend está optimizado para manejar solicitudes de manera rápida y segura, garantizando una experiencia fluida para los usuarios finales.

La principal responsabilidad del backend es servir como intermediario entre la base de datos y el frontend, procesando las solicitudes del cliente, interactuando con la base de datos y devolviendo respuestas en un formato que el frontend pueda consumir y presentar al usuario. Además de las operaciones CRUD básicas para libros, intercambios y opiniones, el backend también gestiona la autenticación y autorización de usuarios, asegurando que solo los usuarios autenticados puedan realizar ciertas acciones y que la información sensible esté protegida.

Con una arquitectura basada en contenedores, el backend se ejecuta en Docker, lo que facilita la escalabilidad, el despliegue y la gestión del entorno de ejecución. Esto asegura que el sistema pueda manejar un gran número de usuarios simultáneos y crecer según las necesidades del proyecto.

## Descripción

La plataforma permite:

-   A los usuarios ofrecer libros para préstamo.
-   A otros usuarios solicitar el préstamo de esos libros.
-   Dejar opiniones sobre los libros una vez realizados los intercambios.
-   Gestionar perfiles de usuario, incluyendo la lista de libros ofrecidos y las opiniones dejadas.

## Requisitos

-   PHP versión 8.1
-   Docker y Docker-Compose
-   Base de datos MySQL (Configurada en Docker)

## Dependencias Principales

-   Laravel versión 10.10
-   Tymon JWT Auth para manejo de tokens JWT

## Configuración

1. Clonar el repositorio: `git clone https://github.com/cbmella/book-exchange`
2. Navegar al directorio del backend.
3. Asegúrate de que Docker esté ejecutándose y luego inicia los contenedores con `docker-compose up -d`.
4. Ejecuta comandos dentro del contenedor, por ejemplo, para instalar dependencias: `docker-compose exec backend composer install`.
5. Copia `.env.example` a `.env` y configura las variables de entorno, especialmente las relacionadas con la base de datos:
    - `DB_CONNECTION=mysql`
    - `DB_HOST=db`
    - `DB_PORT=3306`
    - `DB_DATABASE=laravel_db`
    - `DB_USERNAME=laravel_user`
    - `DB_PASSWORD=laravel_password`
6. Ejecuta migraciones dentro del contenedor: `docker-compose exec backend php artisan migrate`.

## API Endpoints

### Autenticación:

-   `POST /api/login`: Iniciar sesión.
-   `POST /api/register`: Registrar un nuevo usuario.
-   `POST /api/logout`: Cerrar sesión (requiere autenticación).

### Libros:

-   `GET /api/books/search`: Buscar libros por título o autor.
-   `GET /api/books`: Listar todos los libros.
-   `POST /api/books`: Crear un nuevo libro (requiere autenticación).
-   `GET /api/books/{book}`: Ver detalles de un libro específico.
-   `PUT /api/books/{book}`: Actualizar un libro (requiere autenticación).
-   `DELETE /api/books/{book}`: Eliminar un libro (requiere autenticación).

### Intercambios:

-   `POST /api/books/{book}/request-exchange`: Solicitar un intercambio de libro (requiere autenticación).
-   `PATCH /api/exchanges/{exchange}/accept`: Aceptar una solicitud de intercambio (requiere autenticación).

### Opiniones:

-   `POST /api/exchanges/{exchange}/review`: Dejar una opinión sobre un intercambio (requiere autenticación).

## Testing

Para garantizar la calidad y el correcto funcionamiento del backend, se han implementado pruebas unitarias y de características utilizando PHPUnit.

### Ejecución de Pruebas

Para ejecutar todas las pruebas del proyecto, utiliza el siguiente comando:

```bash
php artisan test
```

### Convenciones de Pruebas

-   **Nomenclatura**: Las pruebas deben seguir una nomenclatura descriptiva. Por ejemplo, `it_allows_user_to_register` o `a_user_can_login_with_valid_credentials`.
-   **Organización**: Las pruebas están organizadas en dos directorios principales en la carpeta `tests`: `Feature` y `Unit`.
    -   Las pruebas de características (Feature tests) se encuentran en el directorio `Feature`. Ejemplo: `backend/tests/Feature/AuthControllerTest.php`.
    -   Las pruebas unitarias (Unit tests) se encuentran en el directorio `Unit`. Ejemplo: `backend/tests/Unit/BookTest.php`.
-   **Datos de Prueba**: Utiliza la biblioteca `Faker` para generar datos de prueba aleatorios y realistas. Esto garantiza que las pruebas no dependan de datos estáticos y puedan cubrir una amplia gama de escenarios.

-   **Base de Datos**: Las pruebas deben usar la base de datos

en memoria o una base de datos de prueba separada para no afectar los datos reales. Utiliza el trait `RefreshDatabase` para restablecer el estado de la base de datos después de cada prueba.

### Cobertura de Pruebas

Asegúrate de escribir pruebas para todas las funcionalidades críticas y de cubrir tanto los escenarios exitosos como los escenarios de error. La cobertura de pruebas es esencial para detectar problemas antes de que lleguen a producción.

## Autenticación

La autenticación en el sistema se maneja exclusivamente con JWT (JSON Web Tokens) a través de la biblioteca Tymon JWT Auth.

## Contribuciones

Si deseas contribuir al proyecto, por favor sigue las convenciones y estructuras ya establecidas. Asegúrate de ejecutar y pasar todas las pruebas antes de enviar un Pull Request.
