@extends('layouts.portal')

@section('title', 'Editar período | Portal EDMA')

@section('page-title', 'Editar período')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Editar período académico</h1>

            <p>
                Actualice la planificación académica, matrícula
                y configuración de calificaciones de
                {{ $periodo->nombre }}.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route(
                    'portal.periodos.show',
                    $periodo
                ) }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver al período
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route(
            'portal.periodos.update',
            $periodo
        ) }}"
        method="POST"
        novalidate
    >
        @csrf
        @method('PUT')

        @include('portal.periodos.partials._form', [
            'periodo' => $periodo,
            'modoEdicion' => true,
        ])

    </form>

@endsection