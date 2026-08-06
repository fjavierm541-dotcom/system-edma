@extends('layouts.portal')

@section('title', 'Dashboard | Portal EDMA')

@section('page-title', 'Dashboard')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Resumen general
            </span>

            <h1>Bienvenido al Portal EDMA</h1>

            <p>
                Consulte el estado general de la gestión académica
                y acceda rápidamente a los módulos del sistema.
            </p>
        </div>

        <div class="portal-page-actions">

            <span class="portal-current-date">
                <i class="bi bi-calendar3"></i>

                {{ now()->translatedFormat('d \d\e F \d\e Y') }}
            </span>

        </div>

    </div>

@endsection

@section('content')

    <section class="portal-stat-grid">

        <article class="portal-stat-card">

            <div class="portal-stat-icon">
                <i class="bi bi-person-vcard"></i>
            </div>

            <div class="portal-stat-content">
                <span>Personas registradas</span>
                <strong>0</strong>
                <small>Información general del sistema</small>
            </div>

        </article>

        <article class="portal-stat-card">

            <div class="portal-stat-icon">
                <i class="bi bi-mortarboard"></i>
            </div>

            <div class="portal-stat-content">
                <span>Estudiantes activos</span>
                <strong>0</strong>
                <small>Con expediente activo</small>
            </div>

        </article>

        <article class="portal-stat-card">

            <div class="portal-stat-icon">
                <i class="bi bi-person-workspace"></i>
            </div>

            <div class="portal-stat-content">
                <span>Docentes activos</span>
                <strong>0</strong>
                <small>Personal docente registrado</small>
            </div>

        </article>

        <article class="portal-stat-card portal-stat-card-highlight">

            <div class="portal-stat-icon">
                <i class="bi bi-file-earmark-check"></i>
            </div>

            <div class="portal-stat-content">
                <span>Solicitudes pendientes</span>
                <strong>0</strong>
                <small>Pendientes de revisión</small>
            </div>

        </article>

    </section>

    <div class="row g-4">

        <div class="col-12 col-xl-8">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>
                        <h2>Actividad reciente</h2>
                        <p>
                            Últimos movimientos registrados en el sistema.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btn portal-btn-secondary btn-sm"
                    >
                        Ver actividad
                    </button>

                </div>

                <div class="portal-empty-state">

                    <div class="portal-empty-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <h3>No hay actividad reciente</h3>

                    <p>
                        Los registros y actualizaciones realizadas
                        aparecerán en este espacio.
                    </p>

                </div>

            </section>

        </div>

        <div class="col-12 col-xl-4">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>
                        <h2>Accesos rápidos</h2>
                        <p>Operaciones frecuentes.</p>
                    </div>

                </div>

                <div class="portal-quick-actions">

                    <a
                        href="#"
                        class="portal-quick-action"
                    >
                        <span>
                            <i class="bi bi-person-plus"></i>
                        </span>

                        <div>
                            <strong>Nueva persona</strong>
                            <small>Registrar información personal</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a
                        href="#"
                        class="portal-quick-action"
                    >
                        <span>
                            <i class="bi bi-file-earmark-plus"></i>
                        </span>

                        <div>
                            <strong>Revisar solicitudes</strong>
                            <small>Solicitudes de inscripción</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a
                        href="#"
                        class="portal-quick-action"
                    >
                        <span>
                            <i class="bi bi-people"></i>
                        </span>

                        <div>
                            <strong>Consultar grupos</strong>
                            <small>Grupos y cupos disponibles</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                </div>

            </section>

        </div>

    </div>

@endsection