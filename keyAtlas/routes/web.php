<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

use App\Http\Controllers\SystemController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShortcutController;
use App\Http\Controllers\ShortcutAliasController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';


Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/buscar', function () {
    return view('buscar');
});

Route::get('/applications_view', function () {
    return view('applications_view');
});

Route::get('/applications_details', function () {
    return view('applications_details');
});

Route::get('/mis_shortcuts', function() {
    return view('mis_shortcuts');
})->name('shortcuts.create')
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);



Route::apiResource('systems', SystemController::class)->withoutMiddleware('web');

///


//Appi's necesarias para la página de inicio:

Route::get('applications/search', [ApplicationController::class, 'search'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
// busqueda de aplicaciones por nombre


Route::get('/search', [SearchController::class, 'search'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
// búsqueda completa de atajos con opción de filtros

Route::get('categories/random', [CategoryController::class, 'random'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
// obtener 10 categorías aleatorias para mostrar en la página de inicio



////



//Appi's necesarias para la página de Aplicaiones:

Route::get('/applications-list', [ApplicationController::class, 'list'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
// lista y búsqueda paginada de aplicaciones con filtros opcionales


///


// App view for Application Details: render Blade page that fetches JSON from the API
Route::get('/programas/{id}', [ApplicationController::class, 'show'])
    ->name('applications.show');
//vista de detalles de la aplicación


///


//Appi's necesarias para la página de Mis Shortcuts:
Route::post('/mis-shortcuts', [ShortcutController::class, 'store'])
    ->name('shortcuts.store')
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
//guardar un atajo propio (mis shortcuts)




///

///Rutas API RESTful estándar para los recursos principales:
Route::apiResource('applications', ApplicationController::class)->withoutMiddleware('web');

Route::apiResource('categories', CategoryController::class)->withoutMiddleware('web');

Route::apiResource('shortcuts', ShortcutController::class)->withoutMiddleware('web');

Route::apiResource('shortcut-aliases', ShortcutAliasController::class)->withoutMiddleware('web');



