<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;
use App\Models\CollectionShortcut;
use App\Models\User;
use App\Models\Shortcut;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario demo
        $user = User::factory()->create(['email' => 'demo@example.com']);

        // Colección
        $collection = Collection::create([
            'user_id' => $user->id,
            'name' => 'Mis atajos favoritos'
        ]);

        // Añadir algunos shortcuts
        $shortcut1 = Shortcut::where('keys', 'Ctrl + C')->first()->id;
        $shortcut2 = Shortcut::where('keys', 'Ctrl + T')->first()->id;

        CollectionShortcut::insert([
            ['collection_id' => $collection->id, 'shortcut_id' => $shortcut1],
            ['collection_id' => $collection->id, 'shortcut_id' => $shortcut2],
        ]);
    }
}
