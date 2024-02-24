<?php

namespace Modules\User\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Modules\User\Facades\UserRepository;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actAsAdmin();
    }

    protected function actAsAdmin()
    {
        $admin = UserRepository::admin()->first();
        Passport::actingAs($admin, [], 'admin');
    }

    public function test_admin_can_create_user(): void
    {
        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['user'],
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201);

        $newUserId = $response->json('data.id');
        $userExists = UserRepository::whereKey($newUserId)->exists();

        $this->assertTrue($userExists);
    }

    public function test_admin_can_view_users(): void
    {
        $user = UserRepository::factory()->create();

        $response = $this->getJson("/api/users");

        $response->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }

    public function test_reject_weak_password(): void
    {
        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'pas',
            'password_confirmation' => 'password',
            'roles' => ['user'],
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertInvalid(['password']);
    }

    public function test_admin_can_update_user(): void
    {
        $user = UserRepository::factory()->create();
        $name = $this->faker()->name();

        $userData = compact('name');

        $response = $this->putJson("/api/users/{$user->id}", $userData);

        $response->assertStatus(200);

        $response->assertJsonPath('data.name', $name);
    }

    public function test_admin_can_delete_user(): void
    {
        $user = UserRepository::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);

        $userStillExists = UserRepository::whereKey($user->id)->exists();

        $this->assertFalse($userStillExists);
    }

    public function test_admin_can_view_user(): void
    {
        $user = UserRepository::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }
}
