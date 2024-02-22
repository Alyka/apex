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

    protected function setUp(): void
    {
        parent::setUp();
        $this->actAsUser();
    }

    protected function actAsUser()
    {
        $user = UserRepository::userOnly()->first();
        Passport::actingAs($user, [], 'user');
    }

    public function test_non_admin_cannot_create_user(): void
    {
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

    public function test_non_admin_cannot_update_user(): void
    {
        $user = UserRepository::factory()->create();
        $name = $this->faker()->name();

        $userData = compact('name');

        $response = $this->putJson("/api/users/{$user->id}", $userData);

        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_delete_user(): void
    {
        $user = UserRepository::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_view_user(): void
    {
        $user = UserRepository::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(403);
    }
}
