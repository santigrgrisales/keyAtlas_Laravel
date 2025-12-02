<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Edición',
                'description' => 'Acciones relacionadas con modificar contenido o elementos.'
            ],
            [
                'name' => 'Navegación',
                'description' => 'Atajos usados para moverse entre elementos, ventanas o secciones.'
            ],
            [
                'name' => 'Gestión de Archivos',
                'description' => 'Comandos para abrir, guardar, buscar o manipular archivos.'
            ],
            [
                'name' => 'Productividad',
                'description' => 'Funciones que aceleran tareas comunes o repetitivas.'
            ],
            [
                'name' => 'Ventanas',
                'description' => 'Atajos para manejar ventanas, escritorios o vistas.'
            ],
            [
                'name' => 'Texto',
                'description' => 'Operaciones enfocadas en formato, escritura o selección de texto.'
            ],
            [
                'name' => 'Terminal',
                'description' => 'Comandos y accesos rápidos relacionados con la terminal o consola.'
            ],
        ]);
    }
}
