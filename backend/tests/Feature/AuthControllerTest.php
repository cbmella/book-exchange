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
}
