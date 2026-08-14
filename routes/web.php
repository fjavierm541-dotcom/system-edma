<?php

use App\Http\Controllers\Portal\EmpleadoController;
use App\Http\Controllers\Portal\EstudianteController;
use App\Http\Controllers\Portal\EstudianteResponsableController;
use App\Http\Controllers\Portal\PersonaController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\FormacionAcademicaController;
use App\Http\Controllers\Portal\CuentaBancariaController;
use App\Http\Controllers\Portal\DocenteController;
use App\Http\Controllers\Portal\ProgramaController;
use App\Http\Controllers\Portal\NivelController;
use App\Http\Controllers\Portal\PeriodoAcademicoController;
use App\Http\Controllers\Portal\HorarioController;
use App\Http\Controllers\Portal\GrupoController;


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

        /*
        |--------------------------------------------------------------------------
        | Personas
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'personas/{persona}/estado',
            [PersonaController::class, 'cambiarEstado']
        )->name('personas.cambiar-estado');

        Route::resource(
            'personas',
            PersonaController::class
        )->except('destroy');

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

        /*
        |--------------------------------------------------------------------------
        | Responsables de estudiantes
        |--------------------------------------------------------------------------
        */

        Route::post(
            'estudiantes/{estudiante}/responsables',
            [EstudianteResponsableController::class, 'store']
        )->name('estudiantes.responsables.store');

        Route::put(
            'estudiantes/{estudiante}/responsables/{responsable}',
            [EstudianteResponsableController::class, 'update']
        )->name('estudiantes.responsables.update');

        Route::patch(
            'estudiantes/{estudiante}/responsables/{responsable}/estado',
            [EstudianteResponsableController::class, 'cambiarEstado']
        )->name('estudiantes.responsables.cambiar-estado');

        /*
        |--------------------------------------------------------------------------
        | Empleados
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'empleados/{empleado}/estado',
            [EmpleadoController::class, 'cambiarEstado']
        )->name('empleados.cambiar-estado');

        Route::resource(
            'empleados',
            EmpleadoController::class
        )->except('destroy');


                /*
        |--------------------------------------------------------------------------
        | Formación académica
        |--------------------------------------------------------------------------
        */

        Route::post(
            'empleados/{empleado}/formaciones-academicas',
            [FormacionAcademicaController::class, 'store']
        )->name('empleados.formaciones-academicas.store');

        Route::put(
            'empleados/{empleado}/formaciones-academicas/{formacion}',
            [FormacionAcademicaController::class, 'update']
        )->name('empleados.formaciones-academicas.update');

        Route::patch(
            'empleados/{empleado}/formaciones-academicas/{formacion}/estado',
            [FormacionAcademicaController::class, 'cambiarEstado']
        )->name('empleados.formaciones-academicas.cambiar-estado');



        /*
        |--------------------------------------------------------------------------
        | Cuentas bancarias
        |--------------------------------------------------------------------------
        */

        Route::post(
            'empleados/{empleado}/cuentas-bancarias',
            [CuentaBancariaController::class, 'store']
        )->name('empleados.cuentas-bancarias.store');

        Route::put(
            'empleados/{empleado}/cuentas-bancarias/{cuenta}',
            [CuentaBancariaController::class, 'update']
        )->name('empleados.cuentas-bancarias.update');

        Route::patch(
            'empleados/{empleado}/cuentas-bancarias/{cuenta}/estado',
            [CuentaBancariaController::class, 'cambiarEstado']
        )->name('empleados.cuentas-bancarias.cambiar-estado');


        /*
        |--------------------------------------------------------------------------
        | Docentes
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'docentes/{docente}/estado',
            [DocenteController::class, 'cambiarEstado']
        )->name('docentes.cambiar-estado');

        Route::resource(
            'docentes',
            DocenteController::class
        )->except('destroy');

        /*
        |--------------------------------------------------------------------------
        | Programas académicos
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'programas/{programa}/estado',
            [ProgramaController::class, 'cambiarEstado']
        )->name('programas.cambiar-estado');

        Route::resource(
            'programas',
            ProgramaController::class
        )->except('destroy');

            /*
    |--------------------------------------------------------------------------
    | Niveles académicos
    |--------------------------------------------------------------------------
    */

    Route::patch(
        'niveles/{nivel}/estado',
        [NivelController::class, 'cambiarEstado']
    )->name('niveles.cambiar-estado');

    Route::resource(
        'niveles',
        NivelController::class
    )
        ->parameters([
            'niveles' => 'nivel',
        ])
        ->except('destroy');


        /*
    |--------------------------------------------------------------------------
    | Períodos académicos
    |--------------------------------------------------------------------------
    */

    Route::patch(
        'periodos/{periodo}/estado',
        [PeriodoAcademicoController::class, 'cambiarEstado']
    )->name('periodos.cambiar-estado');

    Route::resource(
        'periodos',
        PeriodoAcademicoController::class
    )
        ->parameters([
            'periodos' => 'periodo',
        ])
        ->except('destroy');


        /*
    |--------------------------------------------------------------------------
    | Horarios
    |--------------------------------------------------------------------------
    */

    Route::patch(
        'horarios/{horario}/estado',
        [HorarioController::class, 'cambiarEstado']
    )->name('horarios.cambiar-estado');

    Route::resource(
        'horarios',
        HorarioController::class
    )
        ->parameters([
            'horarios' => 'horario',
        ])
        ->except('destroy');


        /*
        |--------------------------------------------------------------------------
        | Grupos académicos
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'grupos/{grupo}/estado',
            [GrupoController::class, 'cambiarEstado']
        )->name('grupos.cambiar-estado');

        Route::resource(
            'grupos',
            GrupoController::class
        )
            ->parameters([
                'grupos' => 'grupo',
            ])
            ->except('destroy');

    });

/*
|--------------------------------------------------------------------------
| Campus virtual
|--------------------------------------------------------------------------
*/

Route::get('/campus', [WebsiteController::class, 'campus'])
    ->name('website.campus');

/*
|--------------------------------------------------------------------------
| Comandos útiles de desarrollo
|--------------------------------------------------------------------------
|
| npm run dev
| php artisan storage:link
|
*/