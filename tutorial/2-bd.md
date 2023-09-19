### 2. Diseño de la base de datos

1. **Iniciar una nueva feature en GitFlow**:
```bash
git flow feature start design-database
```

2. **Modelo y Migración de User**:
```bash
php artisan make:model User -m
```
   - Edita el archivo de migración generado en `database/migrations/`.
     - Define las columnas: `name`, `email`, `password`.
     - Añade soft deletes: `$table->softDeletes();`.

3. **Commit**:
```bash
git add database/migrations/
git commit -m "feat(db): define user table structure"
```

4. **Modelo y Migración de Book**:
```bash
php artisan make:model Book -m
```
   - En la migración, define columnas como `title`, `author`, `published_date`, etc.
   - Establece una relación con `User` mediante una clave foránea: `user_id`.

5. **Commit**:
```bash
git add database/migrations/
git commit -m "feat(db): define book table structure and relationship to user"
```

6. **Modelo y Migración de Exchange**:
```bash
php artisan make:model Exchange -m
```
   - Define columnas como `borrower_id`, `lender_id`, `book_id`, `exchange_date`, etc.
   - Establece relaciones con `User` y `Book` mediante claves foráneas.

7. **Modelo y Migración de Review**:
```bash
php artisan make:model Review -m
```
   - Define columnas como `user_id`, `book_id`, `rating`, `comment`.
   - Establece relaciones con `User` y `Book`.

8. **Commit**:
```bash
git add database/migrations/
git commit -m "feat(db): define exchange and review tables with relationships"
```

9. **Definición de Relaciones en Modelos**:
   - En `User.php`, define relaciones como `books()`, `exchanges()`, `reviews()`.
   - En `Book.php`, define relaciones como `users()`, `exchanges()`, `reviews()`.
   - En `Exchange.php`, define relaciones como `borrower()`, `lender()`, `book()`.
   - En `Review.php`, define relaciones con `user()` y `book()`.

10. **Commit**:
```bash
git add app/Models/
git commit -m "feat(models): define eloquent relationships"
```

11. **Pruebas de Relaciones**:
   - Desarrolla pruebas que verifiquen las relaciones de cada modelo.
   - Por ejemplo, para `User`, verifica que un usuario puede tener múltiples libros, intercambios y reseñas.

12. **Commit**:
```bash
git add tests/Feature/
git commit -m "test(models): verify eloquent relationships"
```

13. **Ejecución de Migraciones**:
```bash
docker exec -it [nombre_del_contenedor_backend] bash
php artisan migrate
```

14. **Commit**:
```bash
git add database/migrations/
git commit -m "feat(db): run migrations and set up database tables"
```

15. **Finalizar la feature en GitFlow**:
```bash
git flow feature finish design-database
```