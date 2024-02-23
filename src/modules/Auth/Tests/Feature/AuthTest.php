<?php

namespace Modules\Auth\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Modules\User\Facades\UserRepository;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_user_can_login_with_correct_credentials(): void
    {
        $password = 'password';

        $user = UserRepository::factory()
            ->create(['password' => bcrypt($password)]);

        $email = $user->email;

        $data = compact('email', 'password');

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200);
    }

    public function test_token_is_returned_on_successful_login(): void
    {
        $password = 'password';

        $user = UserRepository::factory()
            ->create(['password' => bcrypt($password)]);

        $email = $user->email;

        $data = compact('email', 'password');

        $response = $this->postJson('/api/login', $data);

        $token = $response->json('data.token');

        $this->assertIsString($token);
    }

    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $user = UserRepository::factory()
            ->create(['password' => bcrypt('password')]);

        $data = [
            'email' => $user->email,
            'password' => 'wrong password',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertInvalid(['login']);
    }
}
