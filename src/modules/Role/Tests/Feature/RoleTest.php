<?php

namespace Modules\Role\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Modules\Role\Facades\RoleRepository;
use Modules\Role\Models\Role;
use Modules\User\Facades\UserRepository;
use Modules\User\Models\User;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use WithFaker;

    protected function createAdmin(): User
    {
        $adminRole = RoleRepository::whereCode(Role::ADMIN)->get();

        return UserRepository::factory()
            ->hasAttached($adminRole)
            ->create();
    }

    public function test_module_configurations_are_idempotent()
    {
        $rolesTableCount = RoleRepository::count();

        $this->artisan('module:config')->assertSuccessful();

        $this->assertDatabaseCount('roles', $rolesTableCount);
    }

    public function test_admin_can_view_roles(): void
    {
        $admin = $this->createAdmin();
        Passport::actingAs($admin, [], 'admin');

        $role = RoleRepository::latest()->first();

        $this->getJson("/api/roles")
            ->assertStatus(200)
            ->assertJsonFragment($role->toArray());
    }

    public function test_admin_can_change_role(): void
    {
        $admin = $this->createAdmin();
        Passport::actingAs($admin, [], 'admin');

        $roles = RoleRepository::where('code', Role::ADMIN)->get();

        $user = UserRepository::factory()
            ->hasAttached($roles)
            ->create();

        $data = ['roles' => [Role::USER]];

        $this->putJson("/api/users/{$user->id}", $data)
            ->assertJsonPath('data.roles', [Role::USER]);
    }
}
