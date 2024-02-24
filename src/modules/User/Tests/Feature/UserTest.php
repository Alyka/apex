<?php

namespace Modules\User\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Modules\User\Facades\UserRepository;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    public function test_new_user_can_register(): void
    {
        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson('/api/users/register', $userData);

        $response->assertStatus(201);
    }

    public function test_rejects_weak_password_during_registration(): void
    {
        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'pas',
            'password_confirmation' => 'pas'
        ];

        $response = $this->postJson('/api/users/register', $userData);

        $response->assertInvalid(['password']);
    }

    public function test_user_can_view_self(): void
    {
        $user = UserRepository::factory()->create();

        Passport::actingAs($user, [], 'user');

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }

    public function test_user_cannot_view_another_user(): void
    {
        $user1 = UserRepository::factory()->create();
        $user2 = UserRepository::factory()->create();

        Passport::actingAs($user1, [], 'user');

        $response = $this->getJson("/api/users/{$user2->id}");

        $response->assertStatus(403);
    }

    public function test_user_cannot_create_another_user(): void
    {
        $user = UserRepository::factory()->create();
        Passport::actingAs($user, [], 'user');

        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['user'],
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_self(): void
    {
        $user = UserRepository::factory()->create();

        Passport::actingAs($user, [], 'user');

        $userData = ['name' => 'New name'];

        $response = $this->putJson("/api/users/{$user->id}", $userData);

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_another_user(): void
    {
        $user1 = UserRepository::factory()->create();
        $user2 = UserRepository::factory()->create();

        Passport::actingAs($user1, [], 'user');

        $userData = ['name' => 'New name'];

        $response = $this->putJson("/api/users/{$user2->id}", $userData);

        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_self(): void
    {
        $user = UserRepository::factory()->create();

        Passport::actingAs($user, [], 'user');

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_another_user(): void
    {
        $user1 = UserRepository::factory()->create();
        $user2 = UserRepository::factory()->create();

        Passport::actingAs($user1, [], 'user');

        $response = $this->deleteJson("/api/users/{$user2->id}");

        $response->assertStatus(403);
    }
}
