@extends('layouts.portal')

@section('title', 'Nuevo estudiante | Portal EDMA')

@section('page-title', 'Nuevo estudiante')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de estudiantes
            </span>

            <h1>Registro manual de estudiante</h1>

            <p>
                Seleccione una persona registrada y complete la información
                propia de su expediente como estudiante.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.estudiantes.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver al listado
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route('portal.estudiantes.store') }}"
        method="POST"
        id="estudianteForm"
        novalidate
    >
        @csrf

        @include('portal.estudiantes.partials._form', [
            'estudiante' => null,
            'modoEdicion' => false,
        ])

    </form>

@endsection