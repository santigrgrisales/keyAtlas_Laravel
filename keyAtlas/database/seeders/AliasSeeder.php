<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alias;
use App\Models\Shortcut;
use App\Models\ShortcutAlias;

class AliasSeeder extends Seeder
{
    public function run(): void
    {
        $copy = Shortcut::where('keys', 'Ctrl + C')->first()->id;

        ShortcutAlias::insert([
            ['shortcut_id' => $copy, 'alias' => "Copiar"],
            ['shortcut_id' => $copy, 'alias' => "Copy"],
            ['shortcut_id' => $copy, 'alias' => "Copiar selección"],
        ]);
    }
}
