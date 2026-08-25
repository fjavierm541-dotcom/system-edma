<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers - Autenticación
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\CambiarPasswordController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Controllers - Portal
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Portal\CalificacionesAdminController;
use App\Http\Controllers\Portal\CalificacionesDocenteController;
use App\Http\Controllers\Portal\MisGruposDocenteController;
use App\Http\Controllers\Portal\InicioDocenteController;
use App\Http\Controllers\Portal\HistorialAcademicoController;
use App\Http\Controllers\Portal\MiPerfilController;
use App\Http\Controllers\Portal\ComprobanteMatriculaController;
use App\Http\Controllers\Portal\CuentaBancariaController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DocenteController;
use App\Http\Controllers\Portal\EmpleadoController;
use App\Http\Controllers\Portal\EstadoCuentaController;
use App\Http\Controllers\Portal\EstudianteController;
use App\Http\Controllers\Portal\EstudianteResponsableController;
use App\Http\Controllers\Portal\FormacionAcademicaController;
use App\Http\Controllers\Portal\GrupoController;
use App\Http\Controllers\Portal\GrupoDocenteController;
use App\Http\Controllers\Portal\GrupoHorarioController;
use App\Http\Controllers\Portal\HorarioController;
use App\Http\Controllers\Portal\InicioEstudianteController;
use App\Http\Controllers\Portal\InicioPortalController;
use App\Http\Controllers\Portal\MiMatriculaController;
use App\Http\Controllers\Portal\NivelController;
use App\Http\Controllers\Portal\PagoAdminController;
use App\Http\Controllers\Portal\PagosEstudianteController;
use App\Http\Controllers\Portal\PeriodoAcademicoController;
use App\Http\Controllers\Portal\PersonaController;
use App\Http\Controllers\Portal\ProgramaController;
use App\Http\Controllers\Portal\SolicitudInscripcionController;
use App\Http\Controllers\Portal\UsuarioController;

/*
|--------------------------------------------------------------------------
| Controllers - Sitio web
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\SolicitudInscripcionPublicaController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Website\ContactoController;
use App\Http\Controllers\Website\ProgramaController as WebsiteProgramaController;


/*
|--------------------------------------------------------------------------
| Página web institucional
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [WebsiteController::class, 'home']
)->name('website.home');

Route::get(
    '/nosotros',
    [WebsiteController::class, 'about']
)->name('website.about');

Route::get(
    '/cursos',
    [WebsiteProgramaController::class, 'index']
)->name('website.courses');

Route::get(
    '/inscripciones',
    [WebsiteController::class, 'admissions']
)->name('website.admissions');

Route::get(
    '/empleos',
    [WebsiteController::class, 'jobs']
)->name('website.jobs');

Route::get(
    '/contacto',
    [WebsiteController::class, 'contact']
)->name('website.contact');

Route::post(
    '/contacto',
    [ContactoController::class, 'store']
)->name('website.contact.store');


/*
|--------------------------------------------------------------------------
| Solicitud pública de inscripción
|--------------------------------------------------------------------------
|
| Estas rutas son públicas.
| El aspirante todavía no posee una cuenta en el sistema.
|
*/

Route::prefix('inscripciones')
    ->name('inscripciones.')
    ->group(function () {

        Route::get(
            '/solicitud',
            [
                SolicitudInscripcionPublicaController::class,
                'create',
            ]
        )->name('solicitud');

        Route::post(
            '/solicitud',
            [
                SolicitudInscripcionPublicaController::class,
                'store',
            ]
        )->name('solicitud.store');

        Route::get(
            '/solicitud/enviada/{codigo}',
            [
                SolicitudInscripcionPublicaController::class,
                'success',
            ]
        )->name('solicitud.exito');
    });


/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Inicio de sesión
|--------------------------------------------------------------------------
|
| Solo pueden acceder usuarios que NO tengan
| una sesión iniciada.
|
*/

Route::middleware('guest')
    ->group(function () {

        Route::get(
            '/login',
            [LoginController::class, 'create']
        )->name('login');

        Route::post(
            '/login',
            [LoginController::class, 'store']
        )->name('login.store');
    });


/*
|--------------------------------------------------------------------------
| Acciones para usuarios autenticados
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Cambio obligatorio de contraseña
        |--------------------------------------------------------------------------
        |
        | Estas rutas no utilizan password.change.required
        | porque deben estar disponibles precisamente cuando
        | debe_cambiar_password = true.
        |
        */

        Route::get(
            '/cambiar-password',
            [CambiarPasswordController::class, 'edit']
        )->name('password.change.edit');

        Route::put(
            '/cambiar-password',
            [CambiarPasswordController::class, 'update']
        )->name('password.change.update');


        /*
        |--------------------------------------------------------------------------
        | Cerrar sesión
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/logout',
            [LoginController::class, 'destroy']
        )->name('logout');
    });


/*
|--------------------------------------------------------------------------
| EDMA PORTAL
|--------------------------------------------------------------------------
|
| Todas las rutas del Portal requieren:
|
| 1. Usuario autenticado.
| 2. Haber cambiado la contraseña temporal.
|
| Después, cada área aplica el middleware
| correspondiente según el rol.
|
*/

Route::middleware([
        'auth',
        'password.change.required',
    ])
    ->prefix('portal')
    ->name('portal.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ENTRADA GENERAL AL PORTAL
        |--------------------------------------------------------------------------
        |
        | /portal es únicamente la puerta de entrada.
        |
        | InicioPortalController determina el rol del usuario
        | y lo dirige a su Inicio correspondiente:
        |
        | Administrador -> portal.admin.inicio
        | Estudiante    -> portal.estudiante.inicio
        | Docente       -> portal.docente.inicio (cuando se habilite)
        |
        */

        Route::get(
            '/',
            InicioPortalController::class
        )->name('inicio');


        /*
        |--------------------------------------------------------------------------
        |--------------------------------------------------------------------------
        | RUTAS DEL ADMINISTRADOR
        |--------------------------------------------------------------------------
        |--------------------------------------------------------------------------
        |
        | TODAS las funcionalidades exclusivas de Administración
        | deben agregarse dentro de este bloque.
        |
        */

        Route::middleware('rol:Administrador')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | INICIO DEL ADMINISTRADOR
                |--------------------------------------------------------------------------
                |
                | La vista actual que antes llamábamos "Dashboard"
                | pasa a mostrarse como "Inicio".
                |
                */

                Route::get(
                    'admin/inicio',
                    [
                        DashboardController::class,
                        'index',
                    ]
                )->name('admin.inicio');


                /*
                |--------------------------------------------------------------------------
                | Compatibilidad temporal con la antigua ruta /dashboard
                |--------------------------------------------------------------------------
                |
                | Cuando terminemos de actualizar sidebar/navbar/enlaces,
                | esta redirección podrá eliminarse.
                |
                */

                Route::get(
                    'dashboard',
                    function () {
                        return redirect()
                            ->route(
                                'portal.admin.inicio'
                            );
                    }
                )->name('dashboard');


                /*
                |--------------------------------------------------------------------------
                | Usuarios y acceso al sistema
                |--------------------------------------------------------------------------
                */

                Route::get(
                    'usuarios',
                    [UsuarioController::class, 'index']
                )->name('usuarios.index');

                Route::get(
                    'usuarios/crear',
                    [UsuarioController::class, 'create']
                )->name('usuarios.create');

                Route::post(
                    'usuarios',
                    [UsuarioController::class, 'store']
                )->name('usuarios.store');

                Route::patch(
                    'usuarios/{usuario}/estado',
                    [
                        UsuarioController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'usuarios.cambiar-estado'
                );

                Route::patch(
                    'usuarios/{usuario}/restablecer-password',
                    [
                        UsuarioController::class,
                        'restablecerPassword',
                    ]
                )->name(
                    'usuarios.restablecer-password'
                );


                /*
                |--------------------------------------------------------------------------
                | Personas
                |--------------------------------------------------------------------------
                */

                Route::patch(
                    'personas/{persona}/estado',
                    [
                        PersonaController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'personas.cambiar-estado'
                );

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
                    [
                        EstudianteController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'estudiantes.cambiar-estado'
                );

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
                    [
                        EstudianteResponsableController::class,
                        'store',
                    ]
                )->name(
                    'estudiantes.responsables.store'
                );

                Route::put(
                    'estudiantes/{estudiante}/responsables/{responsable}',
                    [
                        EstudianteResponsableController::class,
                        'update',
                    ]
                )->name(
                    'estudiantes.responsables.update'
                );

                Route::patch(
                    'estudiantes/{estudiante}/responsables/{responsable}/estado',
                    [
                        EstudianteResponsableController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'estudiantes.responsables.cambiar-estado'
                );


                /*
                |--------------------------------------------------------------------------
                | Empleados
                |--------------------------------------------------------------------------
                */

                Route::patch(
                    'empleados/{empleado}/estado',
                    [
                        EmpleadoController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'empleados.cambiar-estado'
                );

                Route::resource(
                    'empleados',
                    EmpleadoController::class
                )->except('destroy');


                /*
                |--------------------------------------------------------------------------
                | Formación académica de empleados
                |--------------------------------------------------------------------------
                */

                Route::post(
                    'empleados/{empleado}/formaciones-academicas',
                    [
                        FormacionAcademicaController::class,
                        'store',
                    ]
                )->name(
                    'empleados.formaciones-academicas.store'
                );

                Route::put(
                    'empleados/{empleado}/formaciones-academicas/{formacion}',
                    [
                        FormacionAcademicaController::class,
                        'update',
                    ]
                )->name(
                    'empleados.formaciones-academicas.update'
                );

                Route::patch(
                    'empleados/{empleado}/formaciones-academicas/{formacion}/estado',
                    [
                        FormacionAcademicaController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'empleados.formaciones-academicas.cambiar-estado'
                );


                /*
                |--------------------------------------------------------------------------
                | Cuentas bancarias de empleados
                |--------------------------------------------------------------------------
                */

                Route::post(
                    'empleados/{empleado}/cuentas-bancarias',
                    [
                        CuentaBancariaController::class,
                        'store',
                    ]
                )->name(
                    'empleados.cuentas-bancarias.store'
                );

                Route::put(
                    'empleados/{empleado}/cuentas-bancarias/{cuenta}',
                    [
                        CuentaBancariaController::class,
                        'update',
                    ]
                )->name(
                    'empleados.cuentas-bancarias.update'
                );

                Route::patch(
                    'empleados/{empleado}/cuentas-bancarias/{cuenta}/estado',
                    [
                        CuentaBancariaController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'empleados.cuentas-bancarias.cambiar-estado'
                );


                /*
                |--------------------------------------------------------------------------
                | Docentes
                |--------------------------------------------------------------------------
                */

                Route::patch(
                    'docentes/{docente}/estado',
                    [
                        DocenteController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'docentes.cambiar-estado'
                );

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
                    [
                        ProgramaController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'programas.cambiar-estado'
                );

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
                    [
                        NivelController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'niveles.cambiar-estado'
                );

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
                    [
                        PeriodoAcademicoController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'periodos.cambiar-estado'
                );

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
                    [
                        HorarioController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'horarios.cambiar-estado'
                );

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
                    [
                        GrupoController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'grupos.cambiar-estado'
                );

                Route::resource(
                    'grupos',
                    GrupoController::class
                )
                    ->parameters([
                        'grupos' => 'grupo',
                    ])
                    ->except('destroy');


                /*
                |--------------------------------------------------------------------------
                | Horarios de grupos
                |--------------------------------------------------------------------------
                */

                Route::post(
                    'grupos/{grupo}/horarios',
                    [
                        GrupoHorarioController::class,
                        'store',
                    ]
                )->name(
                    'grupos.horarios.store'
                );

                Route::put(
                    'grupos/{grupo}/horarios/{grupoHorario}',
                    [
                        GrupoHorarioController::class,
                        'update',
                    ]
                )->name(
                    'grupos.horarios.update'
                );

                Route::delete(
                    'grupos/{grupo}/horarios/{grupoHorario}',
                    [
                        GrupoHorarioController::class,
                        'destroy',
                    ]
                )->name(
                    'grupos.horarios.destroy'
                );


                /*
                |--------------------------------------------------------------------------
                | Docentes asignados a grupos
                |--------------------------------------------------------------------------
                */

                Route::post(
                    'grupos/{grupo}/docentes',
                    [
                        GrupoDocenteController::class,
                        'store',
                    ]
                )->name(
                    'grupos.docentes.store'
                );

                Route::put(
                    'grupos/{grupo}/docentes/{grupoDocente}',
                    [
                        GrupoDocenteController::class,
                        'update',
                    ]
                )->name(
                    'grupos.docentes.update'
                );

                Route::patch(
                    'grupos/{grupo}/docentes/{grupoDocente}/estado',
                    [
                        GrupoDocenteController::class,
                        'cambiarEstado',
                    ]
                )->name(
                    'grupos.docentes.cambiar-estado'
                );


                /*
                |--------------------------------------------------------------------------
                | Solicitudes de inscripción
                |--------------------------------------------------------------------------
                */

                Route::patch(
                    'solicitudes-inscripcion/{solicitud}/iniciar-revision',
                    [
                        SolicitudInscripcionController::class,
                        'iniciarRevision',
                    ]
                )->name(
                    'solicitudes-inscripcion.iniciar-revision'
                );

                Route::get(
                    'solicitudes-inscripcion/{solicitud}/comprobantes/{comprobante}',
                    [
                        SolicitudInscripcionController::class,
                        'comprobante',
                    ]
                )->name(
                    'solicitudes-inscripcion.comprobantes.show'
                );

                Route::patch(
                    'solicitudes-inscripcion/{solicitud}/aprobar',
                    [
                        SolicitudInscripcionController::class,
                        'aprobar',
                    ]
                )->name(
                    'solicitudes-inscripcion.aprobar'
                );

                Route::patch(
                    'solicitudes-inscripcion/{solicitud}/rechazar',
                    [
                        SolicitudInscripcionController::class,
                        'rechazar',
                    ]
                )->name(
                    'solicitudes-inscripcion.rechazar'
                );

                Route::resource(
                    'solicitudes-inscripcion',
                    SolicitudInscripcionController::class
                )
                    ->parameters([
                        'solicitudes-inscripcion' =>
                            'solicitud',
                    ])
                    ->only([
                        'index',
                        'show',
                    ]);


                /*
                |--------------------------------------------------------------------------
                | Revisión administrativa de pagos
                |--------------------------------------------------------------------------
                */

                Route::prefix('admin/pagos')
                    ->name('admin.pagos.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                PagoAdminController::class,
                                'index',
                            ]
                        )->name('index');

                        Route::get(
                            '{pago}',
                            [
                                PagoAdminController::class,
                                'show',
                            ]
                        )->name('show');

                        Route::post(
                            '{pago}/aprobar',
                            [
                                PagoAdminController::class,
                                'aprobar',
                            ]
                        )->name('aprobar');

                        Route::post(
                            '{pago}/rechazar',
                            [
                                PagoAdminController::class,
                                'rechazar',
                            ]
                        )->name('rechazar');
                    });


                    /*
                    |--------------------------------------------------------------------------
                    | Calificaciones
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        'admin/calificaciones',
                        [
                            CalificacionesAdminController::class,
                            'index',
                        ]
                    )->name(
                        'admin.calificaciones.index'
                    );

                    Route::get(
                        'admin/calificaciones/periodos/{periodo}',
                        [
                            CalificacionesAdminController::class,
                            'grupos',
                        ]
                    )->name(
                        'admin.calificaciones.grupos'
                    );

                    Route::get(
                        'admin/calificaciones/grupos/{grupo}',
                        [
                            CalificacionesAdminController::class,
                            'grupo',
                        ]
                    )->name(
                        'admin.calificaciones.grupo'
                    );

                    Route::patch(
                        'admin/calificaciones/{calificacion}/rectificar',
                        [
                            CalificacionesAdminController::class,
                            'rectificar',
                        ]
                    )->name(
                        'admin.calificaciones.rectificar'
                    );

                /*
                |--------------------------------------------------------------------------
                | AQUÍ IRÁN LAS PRÓXIMAS RUTAS DE ADMINISTRACIÓN
                |--------------------------------------------------------------------------
                |
                | Ejemplos:
                |
                | - Matrículas administrativas
                | - Reportes
                | - Historial académico
                | - Calificaciones consolidadas
                | - Evaluaciones docentes
                | - Configuración
                |
                */

            });


        /*
        |--------------------------------------------------------------------------
        |--------------------------------------------------------------------------
        | RUTAS DEL ESTUDIANTE
        |--------------------------------------------------------------------------
        |--------------------------------------------------------------------------
        |
        | TODAS las funcionalidades disponibles para estudiantes
        | deben agregarse únicamente dentro de este bloque.
        |
        */

        Route::middleware('rol:Estudiante')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | INICIO DEL ESTUDIANTE
                |--------------------------------------------------------------------------
                */

                Route::get(
                    'estudiante/inicio',
                    [
                        InicioEstudianteController::class,
                        'index',
                    ]
                )->name(
                    'estudiante.inicio'
                );

                /*
                |--------------------------------------------------------------------------
                | Mi perfil
                |--------------------------------------------------------------------------
                */

                Route::get(
                    'mi-perfil',
                    [
                        MiPerfilController::class,
                        'index',
                    ]
                )->name(
                    'mi-perfil.index'
                );


                /*
                |--------------------------------------------------------------------------
                | Mi matrícula
                |--------------------------------------------------------------------------
                */

                Route::get(
                    'mi-matricula',
                    [
                        MiMatriculaController::class,
                        'index',
                    ]
                )->name(
                    'mi-matricula.index'
                );

                Route::post(
                    'mi-matricula',
                    [
                        MiMatriculaController::class,
                        'store',
                    ]
                )->name(
                    'mi-matricula.store'
                );

                Route::post(
                    'mi-matricula/cambiar-grupo',
                    [
                        MiMatriculaController::class,
                        'cambiarGrupo',
                    ]
                )->name(
                    'mi-matricula.cambiar-grupo'
                );


                /*
                |--------------------------------------------------------------------------
                | Comprobante de matrícula
                |--------------------------------------------------------------------------
                */

                Route::get(
                    'comprobante-matricula',
                    [
                        ComprobanteMatriculaController::class,
                        'index',
                    ]
                )->name(
                    'comprobante-matricula.index'
                );


                /*
                |--------------------------------------------------------------------------
                | Pagos del estudiante
                |--------------------------------------------------------------------------
                */

                Route::get(
                    'pagos',
                    [
                        PagosEstudianteController::class,
                        'index',
                    ]
                )->name(
                    'pagos.index'
                );

                Route::post(
                    'pagos',
                    [
                        PagosEstudianteController::class,
                        'store',
                    ]
                )->name(
                    'pagos.store'
                );


                /*
                |--------------------------------------------------------------------------
                | Estado de cuenta
                |--------------------------------------------------------------------------
                */

                Route::get(
                    'estado-cuenta',
                    [
                        EstadoCuentaController::class,
                        'index',
                    ]
                )->name(
                    'estado-cuenta.index'
                );

                /*
                    |--------------------------------------------------------------------------
                    | Historial académico
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        'historial-academico',
                        [
                            HistorialAcademicoController::class,
                            'index',
                        ]
                    )->name(
                        'historial-academico.index'
                    );


                /*
                |--------------------------------------------------------------------------
                | AQUÍ IRÁN LAS PRÓXIMAS RUTAS DEL ESTUDIANTE
                |--------------------------------------------------------------------------
                |
                | Próximos módulos:
                |
                | - Mi perfil
                | - Historial académico
                | - Ajustes de cuenta
                | - Acceso / integración con EDMA Campus
                |
                */

            });


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| RUTAS DEL DOCENTE
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
| TODAS las funcionalidades exclusivas del docente
| deberán agregarse únicamente dentro de este bloque.
|
*/

Route::middleware('rol:Docente')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Inicio
        |--------------------------------------------------------------------------
        */

        Route::get(
            'docente/inicio',
            [
                InicioDocenteController::class,
                'index',
            ]
        )->name(
            'docente.inicio'
        );


        /*
        |--------------------------------------------------------------------------
        | Mis grupos
        |--------------------------------------------------------------------------
        */

        Route::get(
            'docente/mis-grupos',
            [
                MisGruposDocenteController::class,
                'index',
            ]
        )->name(
            'docente.mis-grupos.index'
        );
        
        /*
        |--------------------------------------------------------------------------
        | Detalle de grupo
        |--------------------------------------------------------------------------
        */

        Route::get(
            'docente/mis-grupos/{grupo}',
            [
                MisGruposDocenteController::class,
                'show',
            ]
        )->name(
            'docente.mis-grupos.show'
        );

        /*
            |--------------------------------------------------------------------------
            | Calificaciones
            |--------------------------------------------------------------------------
            */

            Route::get(
                'docente/mis-grupos/{grupo}/calificaciones',
                [
                    CalificacionesDocenteController::class,
                    'edit',
                ]
            )->name(
                'docente.calificaciones.edit'
            );

            Route::put(
                'docente/mis-grupos/{grupo}/calificaciones',
                [
                    CalificacionesDocenteController::class,
                    'update',
                ]
            )->name(
                'docente.calificaciones.update'
            );

            /*
            |--------------------------------------------------------------------------
            | Confirmar calificaciones
            |--------------------------------------------------------------------------
            */

            Route::post(
                'docente/mis-grupos/{grupo}/calificaciones/confirmar',
                [
                    CalificacionesDocenteController::class,
                    'confirmar',
                ]
            )->name(
                'docente.calificaciones.confirmar'
            );


        /*
        |--------------------------------------------------------------------------
        | AQUÍ IRÁN LAS PRÓXIMAS RUTAS DEL DOCENTE
        |--------------------------------------------------------------------------
        |
        | Próximamente:
        |
        | - Detalle de grupo
        | - Estudiantes matriculados
        | - Carga de calificaciones
        | - Borradores
        | - Confirmación de calificaciones
        | - Documento de respaldo
        | - Mi perfil
        |
        */

    });

});


/*
|--------------------------------------------------------------------------
| Campus virtual
|--------------------------------------------------------------------------
*/

Route::get(
    '/campus',
    [WebsiteController::class, 'campus']
)->name('website.campus');


/*
|--------------------------------------------------------------------------
| Comandos útiles de desarrollo
|--------------------------------------------------------------------------
|
| npm run dev
| php artisan storage:link
| php artisan optimize:clear
|
|--------------------------------------------------------------------------
| Primer usuario administrador de pruebas
|--------------------------------------------------------------------------
|
| php artisan tinker
|
| $rol = App\Models\Rol::where(
|     'nombre',
|     'Administrador'
| )->first();
|
| $user = App\Models\User::create([
|     'persona_id' => null,
|     'username' => 'ADMIN-EDMA',
|     'email' => null,
|     'password' => 'Edma2026*',
|     'debe_cambiar_password' => true,
|     'activo' => true,
|     'ultimo_acceso_at' => null,
| ]);
|
| $user->roles()->attach($rol->id);
|
| $user->load('roles');
|
| exit
|
|--------------------------------------------------------------------------
| Credenciales temporales del usuario de pruebas
|--------------------------------------------------------------------------
|
| Usuario:    EDMA-2026-00006
| Contraseña: T4penade.
|
*/