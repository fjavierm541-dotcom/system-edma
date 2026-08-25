@extends('layouts.portal')

@section('title', 'Nuevo período | Portal EDMA')

@section('page-title', 'Nuevo período')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Registrar período académico</h1>

            <p>
                Defina la planificación académica, las fechas de matrícula
                y la ventana de carga de calificaciones del nuevo período.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.periodos.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route('portal.periodos.store') }}"
        method="POST"
        novalidate
    >
        @csrf

        @include('portal.periodos.partials._form', [
            'periodo' => null,
            'modoEdicion' => false,
        ])

    </form>

@endsection