<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shortcut;
use App\Models\Application;
use App\Models\Category;

class ShortcutSeeder extends Seeder
{
    public function run(): void
    {
        $vscode  = Application::where('name', 'Visual Studio Code')->first()->id;
        $word    = Application::where('name', 'Microsoft Word')->first()->id;
        $excel   = Application::where('name', 'Microsoft Excel')->first()->id;
        $chrome  = Application::where('name', 'Google Chrome')->first()->id;
        $windows = Application::where('name', 'Windows OS (General)')->first()->id;

        $edit   = Category::where('name', 'Edición')->first()->id;
        $nav    = Category::where('name', 'Navegación')->first()->id;
        $prod   = Category::where('name', 'Productividad')->first()->id;
        $winCat = Category::where('name', 'Ventanas')->first()->id;

        Shortcut::insert([

            // VS CODE
            [
                'application_id' => $vscode,
                'category_id' => $edit,
                'keys' => 'Ctrl + C',
                'description' => 'Copiar la selección',
            ],
            [
                'application_id' => $vscode,
                'category_id' => $edit,
                'keys' => 'Ctrl + V',
                'description' => 'Pegar',
            ],
            [
                'application_id' => $vscode,
                'category_id' => $prod,
                'keys' => 'Ctrl + P',
                'description' => 'Abrir archivos rápidamente',
            ],

            // WORD
            [
                'application_id' => $word,
                'category_id' => $edit,
                'keys' => 'Ctrl + B',
                'description' => 'Negrita',
            ],
            [
                'application_id' => $word,
                'category_id' => $edit,
                'keys' => 'Ctrl + I',
                'description' => 'Cursiva',
            ],

            // EXCEL
            [
                'application_id' => $excel,
                'category_id' => $prod,
                'keys' => 'Ctrl + Shift + L',
                'description' => 'Activar filtros',
            ],

            // CHROME
            [
                'application_id' => $chrome,
                'category_id' => $nav,
                'keys' => 'Ctrl + T',
                'description' => 'Nueva pestaña',
            ],
            [
                'application_id' => $chrome,
                'category_id' => $nav,
                'keys' => 'Ctrl + Shift + T',
                'description' => 'Reabrir pestaña cerrada',
            ],

            // WINDOWS GENERAL
            [
                'application_id' => $windows,
                'category_id' => $winCat,
                'keys' => 'Alt + Tab',
                'description' => 'Cambiar entre ventanas',
            ],
            [
                'application_id' => $windows,
                'category_id' => $winCat,
                'keys' => 'Win + D',
                'description' => 'Mostrar escritorio',
            ],

        ]);
    }
}
