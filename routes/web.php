<?php

use App\Http\Controllers\Portal\PersonaController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\EstudianteController;


/*
|--------------------------------------------------------------------------
| Página web institucional
|--------------------------------------------------------------------------
*/

Route::get('/', [WebsiteController::class, 'home'])
    ->name('website.home');

Route::get('/nosotros', [WebsiteController::class, 'about'])
    ->name('website.about');

Route::get('/cursos', [WebsiteController::class, 'courses'])
    ->name('website.courses');

Route::get('/inscripciones', [WebsiteController::class, 'admissions'])
    ->name('website.admissions');

Route::get('/empleos', [WebsiteController::class, 'jobs'])
    ->name('website.jobs');

Route::get('/contacto', [WebsiteController::class, 'contact'])
    ->name('website.contact');

/*
|--------------------------------------------------------------------------
| Portal administrativo
|--------------------------------------------------------------------------
*/
//personas
Route::prefix('portal')
    ->name('portal.')
    ->group(function () {
        Route::view('/', 'portal.dashboard')
            ->name('dashboard');

        Route::patch(
            'personas/{persona}/estado',
            [PersonaController::class, 'cambiarEstado']
        )->name('personas.cambiar-estado');

        Route::resource('personas', PersonaController::class)
            ->except('destroy');

             /*
        |--------------------------------------------------------------------------
        | Estudiantes
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'estudiantes/{estudiante}/estado',
            [EstudianteController::class, 'cambiarEstado']
        )->name('estudiantes.cambiar-estado');

        Route::resource(
            'estudiantes',
            EstudianteController::class
        )->except('destroy');
    });


/*
|--------------------------------------------------------------------------
| Campus virtual
|--------------------------------------------------------------------------
*/

Route::get('/campus', [WebsiteController::class, 'campus'])
    ->name('website.campus');


        //para dise;os    npm run dev
        // para fotos   php artisan storage:link