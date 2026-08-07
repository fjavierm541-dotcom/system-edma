@extends('layouts.portal')

@section('title', 'Editar estudiante | Portal EDMA')

@section('page-title', 'Editar estudiante')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de estudiantes
            </span>

            <h1>Editar expediente estudiantil</h1>

            <p>
                Actualice la información académica y administrativa de
                {{ $estudiante->persona->nombre_completo }}.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.estudiantes.show', $estudiante) }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver al expediente
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route('portal.estudiantes.update', $estudiante) }}"
        method="POST"
        id="estudianteForm"
        novalidate
    >
        @csrf
        @method('PUT')

        @include('portal.estudiantes.partials._form', [
            'estudiante' => $estudiante,
            'modoEdicion' => true,
        ])

    </form>

@endsection