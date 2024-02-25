<?php

namespace Modules\User\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Modules\Role\Facades\RoleRepository;
use Modules\Role\Models\Role;
use Modules\User\Database\Seeders\UserTableSeeder;
use Modules\User\Facades\UserRepository;
use Modules\User\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    protected function createUser(): User
    {
        $userRole = RoleRepository::whereCode(Role::USER)->get();

        return UserRepository::factory()
            ->hasAttached($userRole)
            ->create();
    }

    public function test_users_table_seeding()
    {
        $count = UserRepository::count() + 10;

        $this->seed(UserTableSeeder::class)
            ->assertDatabaseCount('users', $count);
    }

    public function test_new_user_can_register(): void
    {
        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->postJson('/api/users/register', $userData)
            ->assertStatus(201);
    }

    public function test_user_cannot_register_with_existing_email(): void
    {
        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->postJson('/api/users/register', $userData)
            ->assertStatus(201);

        $this->postJson('/api/users/register', $userData)
            ->assertStatus(422)
            ->assertInvalid(['email']);
    }

    public function test_rejects_weak_password_during_registration(): void
    {
        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'pas',
            'password_confirmation' => 'pas'
        ];

        $this->postJson('/api/users/register', $userData)
            ->assertInvalid(['password']);
    }

    public function test_user_can_view_self(): void
    {
        $user = $this->createUser();

        Passport::actingAs($user, [], 'user');

        $this->getJson("/api/users/{$user->id}")
            ->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }

    public function test_user_cannot_view_another_user(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        Passport::actingAs($user1, [], 'user');

        $this->getJson("/api/users/{$user2->id}")
            ->assertStatus(403);
    }

    public function test_user_cannot_create_another_user(): void
    {
        $user = $this->createUser();

        Passport::actingAs($user, [], 'user');

        $userData = [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['user'],
        ];

        $this->postJson('/api/users', $userData)
            ->assertStatus(403);
    }

    public function test_user_cannot_update_self(): void
    {
        $user = $this->createUser();

        Passport::actingAs($user, [], 'user');

        $userData = ['name' => 'New name'];

        $this->putJson("/api/users/{$user->id}", $userData)
            ->assertStatus(403);
    }

    public function test_user_cannot_update_another_user(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        Passport::actingAs($user1, [], 'user');

        $userData = ['name' => 'New name'];

        $this->putJson("/api/users/{$user2->id}", $userData)
            ->assertStatus(403);
    }

    public function test_user_cannot_delete_self(): void
    {
        $user = $this->createUser();

        Passport::actingAs($user, [], 'user');

        $this->deleteJson("/api/users/{$user->id}")
            ->assertStatus(403);
    }

    public function test_user_cannot_delete_another_user(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        Passport::actingAs($user1, [], 'user');

        $this->deleteJson("/api/users/{$user2->id}")
            ->assertStatus(403);
    }
}
