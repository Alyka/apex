<?php

namespace Modules\User\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CoreTest extends TestCase
{
    use WithFaker;

    public function test_modules_are_auto_configurable()
    {
        $this->artisan('module:config')
            ->assertSuccessful();
    }
}
