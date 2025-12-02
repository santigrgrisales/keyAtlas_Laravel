<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\System;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $windows = System::where('name', 'Windows')->first()->id;

        Application::insert([
            ['system_id' => $windows, 'name' => 'Visual Studio Code'],
            ['system_id' => $windows, 'name' => 'Microsoft Word'],
            ['system_id' => $windows, 'name' => 'Microsoft Excel'],
            ['system_id' => $windows, 'name' => 'Google Chrome'],
            ['system_id' => $windows, 'name' => 'Windows OS (General)'],
        ]);
    }
}
