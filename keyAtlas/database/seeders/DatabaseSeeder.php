<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        SystemSeeder::class,
        ApplicationSeeder::class,
        CategorySeeder::class,
        ShortcutSeeder::class,
        AliasSeeder::class,
        CollectionSeeder::class,
    ]);
}
}
