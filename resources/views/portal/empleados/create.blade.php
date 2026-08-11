@extends('layouts.portal')

@section('title', 'Nuevo empleado | Portal EDMA')

@section('page-title', 'Nuevo empleado')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de recursos humanos
            </span>

            <h1>Registrar empleado</h1>

            <p>
                Seleccione una persona existente y complete la
                información correspondiente a su expediente laboral.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.empleados.index') }}"
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
        action="{{ route('portal.empleados.store') }}"
        method="POST"
        id="empleadoForm"
        novalidate
    >
        @csrf

        @include('portal.empleados.partials._form', [
            'empleado' => null,
            'modoEdicion' => false,
        ])

    </form>

@endsection