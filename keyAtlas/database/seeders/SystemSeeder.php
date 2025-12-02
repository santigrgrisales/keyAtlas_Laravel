<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        System::insert([
            ['name' => 'Windows'],
            ['name' => 'Linux'],
            ['name' => 'macOS'],
        ]);
    }
}
