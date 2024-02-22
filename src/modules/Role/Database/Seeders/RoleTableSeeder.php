<?php

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Facades\RoleRepository;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoleRepository::factory()
            ->count(10)
            ->create();
    }
}
