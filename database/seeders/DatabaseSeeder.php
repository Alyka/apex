<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Database\Seeders\UserTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
        ]);
    }
}
