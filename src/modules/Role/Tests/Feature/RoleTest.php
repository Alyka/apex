<?php

namespace Modules\Role\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Modules\Role\Facades\RoleRepository;
use Modules\Role\Models\Role;
use Modules\User\Facades\UserRepository;
use Tests\TestCase;

class RoleTest extends TestCase
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

    public function test_admin_can_change_role(): void
    {
        $roles = RoleRepository::where('code', Role::ADMIN)->get();

        $user = UserRepository::factory()
            ->hasAttached($roles)
            ->create();

        $data = ['roles' => [Role::USER]];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $roles = $response->assertJsonPath('data.roles', [Role::USER]);
    }
}
