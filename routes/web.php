<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//Pagina Web rutas

    Route::get('/', [WebsiteController::class, 'home'])->name('website.home');

    Route::get('/nosotros', [WebsiteController::class, 'about'])->name('website.about');

    Route::get('/cursos', [WebsiteController::class, 'courses'])->name('website.courses');

    Route::get('/inscripciones', [WebsiteController::class, 'admissions'])->name('website.admissions');

    Route::get('/empleos', [WebsiteController::class, 'jobs'])->name('website.jobs');

    Route::get('/contacto', [WebsiteController::class, 'contact'])->name('website.contact');


    //RUTA CAMPUS
    Route::get('/campus', [WebsiteController::class, 'campus'])
    ->name('website.campus');