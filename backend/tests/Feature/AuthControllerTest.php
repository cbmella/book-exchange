<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     *  Test that a user can register.
     *
     * @test
     * @return void
     */
    public function a_user_can_register()
    {
        $password = $this->faker->password(8, 20);
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(route('register'), $userData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    /**
     * Test that a user can login with valid credentials.
     *
     * @test
     * @return void
     */
    public function a_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['token']);
    }

    /**
     * Test that a user cannot login with invalid credentials.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'The provided credentials are incorrect.']);
    }

    /**
     * Test that a user cannot login with an invalid email format.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_login_with_invalid_email_format()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'invalidEmailFormat',
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => 'The email field must be a valid email address.']);
    }

    /**
     * Test that a user can logout.
     *
     * @test
     * @return void
     */
    public function a_user_can_logout()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user, ['key' => config('jwt.secret')]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(route('logout'));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertFalse(JWTAuth::setToken($token)->check());
    }

    /**
     * Test that a user cannot register with an email that's already taken.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_register_with_duplicate_email()
    {
        // Create a user with a specific email
        $existingUser = User::factory()->create(['email' => 'existingemail@example.com']);

        // Data for the new user trying to register with the same email
        $password = $this->faker->password(8, 20);
        $userData = [
            'name' => $this->faker->name,
            'email' => 'existingemail@example.com', // Using the same email
            'password' => $password,
            'password_confirmation' => $password,
        ];

        // Attempt to register the new user
        $response = $this->postJson(route('register'), $userData);

        // Assert the response
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The email has already been taken.',
                'errors' => [
                    'email' => ['The email has already been taken.']
                ]
            ]);
    }

    /**
     * Test that a user cannot register without sending all required parameters.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_register_without_all_required_parameters()
    {
        // Data without the 'email' and 'password_confirmation' fields
        $userData = [
            'name' => $this->faker->name,
            'password' => $this->faker->password(8, 20),
        ];

        // Attempt to register the user
        $response = $this->postJson(route('register'), $userData);

        // Assert the response
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Test that a user cannot register without the 'name' field.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_register_without_name()
    {
        $password = $this->faker->password(8, 20);
        $userData = [
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test that a user cannot register without the 'email' field.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_register_without_email()
    {
        $password = $this->faker->password(8, 20);
        $userData = [
            'name' => $this->faker->name,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test that a user cannot register with a short password.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_register_with_short_password()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test that a user cannot register with an invalid email format.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_register_with_invalid_email_format()
    {
        $password = $this->faker->password(8, 20);
        $userData = [
            'name' => $this->faker->name,
            'email' => 'invalidEmail',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test that a user cannot register with mismatched password and confirmation.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_register_with_mismatched_password_and_confirmation()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'password123',
            'password_confirmation' => 'password321',
        ];

        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test that a user can register with leading and trailing whitespaces in fields.
     *
     * @test
     * @return void
     */
    public function a_user_can_register_with_whitespaces_in_fields()
    {
        $password = $this->faker->password(8, 20);
        $userData = [
            'name' => ' ' . $this->faker->name . ' ',
            'email' => ' ' . $this->faker->email . ' ',
            'password' => ' ' . $password . ' ',
            'password_confirmation' => ' ' . $password . ' ',
        ];

        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['token']);
    }

    /**
     * Test that email registration is case-insensitive.
     *
     * @test
     * @return void
     */
    public function email_registration_is_case_insensitive()
    {
        $email = $this->faker->email;
        $password = $this->faker->password(8, 20);

        // Register with lowercase email
        $userData = [
            'name' => $this->faker->name,
            'email' => strtolower($email),
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $this->postJson(route('register'), $userData)
            ->assertStatus(Response::HTTP_CREATED);

        // Attempt to register with uppercase email
        $userData['email'] = strtoupper($email);
        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }


    /**
     * Test that registration is protected against SQL injection.
     *
     * @test
     * @return void
     */
    public function registration_is_protected_against_sql_injection()
    {
        $password = $this->faker->password(8, 20);
        $userData = [
            'name' => $this->faker->name,
            'email' => "testemail' OR '1' = '1'; -- @example.com",
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test that registration endpoint has rate limiting.
     *
     * @test
     * @return void
     */
    public function registration_endpoint_has_rate_limiting()
    {
        $password = $this->faker->password(8, 20);
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        for ($i = 0; $i <= 60; $i++) {  // Assuming a limit of 60 requests per minute
            $response = $this->postJson(route('register'), $userData);
        }

        $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /**
     * Test that the response data does not leak sensitive information.
     *
     * @test
     * @return void
     */
    public function response_data_does_not_leak_sensitive_information()
    {
        $password = $this->faker->password(8, 20);
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(route('register'), $userData);
        $response->assertStatus(Response::HTTP_CREATED);

        $data = $response->json();
        $this->assertArrayNotHasKey('password', $data);
        $this->assertArrayNotHasKey('password_confirmation', $data);
    }
}
