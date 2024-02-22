<?php

namespace Modules\User\Database\Seeders;

use Modules\User\Facades\UserRepository;
use Illuminate\Database\Seeder;
use Modules\Role\Facades\RoleRepository;
use Modules\Role\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = RoleRepository::where('code', Role::USER)->get();

        UserRepository::factory()
            ->count(10)
            ->hasAttached($roles)
            ->create();
    }
}
