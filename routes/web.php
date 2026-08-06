<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('portal')
    ->name('portal.')
    ->group(function () {
        Route::view('/', 'portal.dashboard')
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Campus virtual
|--------------------------------------------------------------------------
*/

Route::get('/campus', [WebsiteController::class, 'campus'])
    ->name('website.campus');
    //npm run dev