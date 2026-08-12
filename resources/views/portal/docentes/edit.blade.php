@extends('layouts.portal')

@section('title', 'Editar docente | Portal EDMA')

@section('page-title', 'Editar docente')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Editar perfil docente</h1>

            <p>
                Actualice la información docente de
                {{ $docente->empleado->persona->nombre_completo }}.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.docentes.show', $docente) }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver al perfil
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route('portal.docentes.update', $docente) }}"
        method="POST"
        id="docenteForm"
        novalidate
    >
        @csrf
        @method('PUT')

        @include('portal.docentes.partials._form', [
            'docente' => $docente,
            'modoEdicion' => true,
        ])

    </form>

@endsection