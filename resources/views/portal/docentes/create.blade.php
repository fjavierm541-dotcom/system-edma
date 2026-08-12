@extends('layouts.portal')

@section('title', 'Nuevo docente | Portal EDMA')

@section('page-title', 'Nuevo docente')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Registrar docente</h1>

            <p>
                Seleccione un empleado activo y complete la
                información correspondiente a su perfil docente.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.docentes.index') }}"
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
        action="{{ route('portal.docentes.store') }}"
        method="POST"
        id="docenteForm"
        novalidate
    >
        @csrf

        @include('portal.docentes.partials._form', [
            'docente' => null,
            'modoEdicion' => false,
        ])

    </form>

@endsection