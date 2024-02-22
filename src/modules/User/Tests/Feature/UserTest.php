<?php

namespace Modules\User\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_admin_can_create_user(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['create-servers']
        );
    }

    public function test_admin_can_update_user(): void
    {

    }

    public function test_admin_can_delete_user(): void
    {

    }

    public function test_admin_can_create_view_user(): void
    {

    }

    public function test_non_admin_cannot_create_user(): void
    {

    }

    public function test_non_admin_cannot_update_user(): void
    {

    }

    public function test_non_admin_cannot_delete_user(): void
    {

    }

    public function test_non_admin_cannot_view_user(): void
    {

    }
}
