<?php

namespace Modules\User\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Modules\Role\Facades\RoleRepository;
use Modules\Role\Models\Role;
use Modules\User\Facades\UserRepository;
use Modules\User\Models\User;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use WithFaker;

    protected function createAdmin(): User
    {
        $adminRole = RoleRepository::whereCode(Role::ADMIN)->get();

        return UserRepository::factory()
            ->hasAttached($adminRole)
            ->create();
    }

    public function test_admin_can_create_user(): void
    {
        $admin = $this->createAdmin();
        Passport::actingAs($admin, [], 'admin');

        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['user'],
        ];

        $this->postJson('/api/users', $userData)
                        ->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    public function test_admin_can_view_users(): void
    {
        $admin = $this->createAdmin();
        Passport::actingAs($admin, [], 'admin');

        $user = UserRepository::factory()->create();

        $this->getJson("/api/users")
            ->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }

    public function test_admin_can_update_user(): void
    {
        $admin = $this->createAdmin();
        Passport::actingAs($admin, [], 'admin');

        $user = UserRepository::factory()->create();
        $name = $this->faker()->name();
        $userData = compact('name');

        $this->putJson("/api/users/{$user->id}", $userData)
            ->assertStatus(200)
            ->assertJsonPath('data.name', $name);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = $this->createAdmin();
        Passport::actingAs($admin, [], 'admin');

        $user = UserRepository::factory()->create();

        $this->deleteJson("/api/users/{$user->id}")
            ->assertStatus(200);

        $this->assertModelMissing($user);
    }

    public function test_admin_can_view_user(): void
    {
        $admin = $this->createAdmin();
        Passport::actingAs($admin, [], 'admin');

        $user = UserRepository::factory()->create();

        $this->getJson("/api/users/{$user->id}")
            ->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }

    public function test_can_create_admin_via_cli()
    {
        $name = $this->faker->name();
        $email = $this->faker->email();

        $this->artisan('module:create-admin')
            ->expectsQuestion('Enter your name', $name)
            ->expectsQuestion('Enter your email', $email)
            ->expectsQuestion('Choose password', 'password')
            ->expectsQuestion('Confirm password', 'password')
            ->expectsTable([
                'name',
                'email',
            ], [
                [$name, $email]
            ]);
    }
}
