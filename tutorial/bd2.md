### 2. Diseño de la base de datos

1. **Iniciar una nueva feature en GitFlow**:
```bash
git flow feature start design-database
```

2. **Modelo y Migración de User**:
```bash
php artisan make:model User -m
```
   - Edita el archivo de migración generado en `database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php`:
     ```php
     public function up()
     {
         Schema::create('users', function (Blueprint $table) {
             $table->id();
             $table->string('name');
             $table->string('email')->unique();
             $table->timestamp('email_verified_at')->nullable();
             $table->string('password');
             $table->rememberToken();
             $table->softDeletes();
             $table->timestamps();
         });
     }
     ```

3. **Commit**:
```bash
git add database/migrations/
git commit -m "feat(db): define user table structure"
```

4. **Modelo y Migración de Book**:
```bash
php artisan make:model Book -m
```
   - Edita el archivo de migración generado en `database/migrations/xxxx_xx_xx_xxxxxx_create_books_table.php`:
     ```php
     public function up()
     {
         Schema::create('books', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('user_id');
             $table->string('title');
             $table->string('author');
             $table->date('published_date');
             $table->timestamps();
             
             $table->foreign('user_id')->references('id')->on('users');
         });
     }
     ```

5. **Commit**:
```bash
git add database/migrations/
git commit -m "feat(db): define book table structure and relationship to user"
```

6. **Modelo y Migración de Exchange**:
```bash
php artisan make:model Exchange -m
```
   - Edita el archivo de migración generado en `database/migrations/xxxx_xx_xx_xxxxxx_create_exchanges_table.php`:
     ```php
     public function up()
     {
         Schema::create('exchanges', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('borrower_id');
             $table->unsignedBigInteger('lender_id');
             $table->unsignedBigInteger('book_id');
             $table->date('exchange_date');
             $table->timestamps();
             
             $table->foreign('borrower_id')->references('id')->on('users');
             $table->foreign('lender_id')->references('id')->on('users');
             $table->foreign('book_id')->references('id')->on('books');
         });
     }
     ```

7. **Modelo y Migración de Review**:
```bash
php artisan make:model Review -m
```
   - Edita el archivo de migración generado en `database/migrations/xxxx_xx_xx_xxxxxx_create_reviews_table.php`:
     ```php
     public function up()
     {
         Schema::create('reviews', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('user_id');
             $table->unsignedBigInteger('book_id');
             $table->tinyInteger('rating');
             $table->text('comment')->nullable();
             $table->timestamps();
             
             $table->foreign('user_id')->references('id')->on('users');
             $table->foreign('book_id')->references('id')->on('books');
         });
     }
     ```

8. **Commit**:
```bash
git add database/migrations/
git commit -m "feat(db): define exchange and review tables with relationships"
```

9. **Definición de Relaciones en Modelos**:
   - **User.php**:
     ```php
     public function books() {
         return $this->hasMany(Book::class);
     }

     public function exchangesAsBorrower() {
         return $this->hasMany(Exchange::class, 'borrower_id');
     }

     public function exchangesAsLender() {
         return $this->hasMany(Exchange::class, 'lender_id');
     }

     public function reviews() {
         return $this->hasMany(Review::class);
     }
     ```

   - **Book.php**:
     ```php
     public function owner() {
         return $this->belongsTo(User::class, 'user_id');
     }

     public function exchanges() {
         return $this->hasMany(Exchange::class);
     }

     public function reviews() {
         return $this->hasMany(Review::class);
     }
     ```

   - **Exchange.php**:
     ```php
     public function borrower() {
         return $this->belongsTo(User::class, 'borrower_id');
     }

     public function lender() {
         return $this->belongsTo(User::class, 'lender_id');
     }

     public function book() {
         return $this->belongsTo(Book::class);
     }
     ```

   - **Review.php**:
     ```php
     public function user() {
         return $this->belongsTo(User::class);
     }

     public function book() {
         return $this->belongsTo(Book::class);
     }
     ```

10. **Commit**:
```bash
git add app/Models/
git commit -m "feat(models): define eloquent relationships"
```

11. **Pruebas de Relaciones**:
   - **UserTest.php**:
     ```php
            public function test_user_has_books() {
                $user = User::factory()->create();
                $book = Book::factory()->create(['user_id' => $user->id]);
                $this->assertTrue($user->books->contains($book));
            }

            public function test_user_has_exchanges_as_borrower() {
                $user = User::factory()->create();
                $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
                $this->assertTrue($user->exchangesAsBorrower->contains($exchange));
            }

            public function test_user_has_exchanges_as_lender() {
                $user = User::factory()->create();
                $exchange = Exchange::factory()->create(['lender_id' => $user->id]);
                $this->assertTrue($user->exchangesAsLender->contains($exchange));
            }

            public function test_user_has_reviews() {
                $user = User::factory()->create();
                $review = Review::factory()->create(['user_id' => $user->id]);
                $this->assertTrue($user->reviews->contains($review));
            }
     ```

   - **BookTest.php**:
     ```php
            public function test_book_belongs_to_owner() {
                $user = User::factory()->create();
                $book = Book::factory()->create(['user_id' => $user->id]);
                $this->assertEquals($user->id, $book->owner->id);
            }

            public function test_book_has_exchanges() {
                $book = Book::factory()->create();
                $exchange = Exchange::factory()->create(['book_id' => $book->id]);
                $this->assertTrue($book->exchanges->contains($exchange));
            }

            public function test_book_has_reviews() {
                $book = Book::factory()->create();
                $review = Review::factory()->create(['book_id' => $book->id]);
                $this->assertTrue($book->reviews->contains($review));
            }
     ```

   - **ExchangeTest.php**:
     ```php
            public function test_exchange_has_borrower() {
                $user = User::factory()->create();
                $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
                $this->assertEquals($user->id, $exchange->borrower->id);
            }

            public function test_exchange_has_lender() {
                $user = User::factory()->create();
                $exchange = Exchange::factory()->create(['lender_id' => $user->id]);
                $this->assertEquals($user->id, $exchange->lender->id);
            }

            public function test_exchange_has_book() {
                $book = Book::factory()->create();
                $exchange = Exchange::factory()->create(['book_id' => $book->id]);
                $this->assertEquals($book->id, $exchange->book->id);
            }
     ```

   - **ReviewTest.php**:
     ```php
            public function test_review_belongs_to_user() {
                $user = User::factory()->create();
                $review = Review::factory()->create(['user_id' => $user->id]);
                $this->assertEquals($user->id, $review->user->id);
            }

            public function test_review_belongs_to_book() {
                $book = Book::factory()->create();
                $review = Review::factory()->create(['book_id' => $book->id]);
                $this->assertEquals($book->id, $review->book->id);
            }
     ```
12. **Commit**:
```bash
git add tests/Feature/
git commit -m "test(models): verify eloquent relationships"
```

13. **Ejecución de Migraciones**:
```bash
docker exec -it [

nombre_del_contenedor_backend] bash
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