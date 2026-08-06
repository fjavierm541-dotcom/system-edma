@extends('layouts.portal')

@section('title', 'Nueva persona | Portal EDMA')

@section('page-title', 'Nueva persona')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de personas
            </span>

            <h1>Registrar nueva persona</h1>

            <p>
                Ingrese la información general de la persona.
                Posteriormente podrá asociarse como estudiante,
                empleado, docente o responsable.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.personas.index') }}"
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
        action="{{ route('portal.personas.store') }}"
        method="POST"
        enctype="multipart/form-data"
        id="personaForm"
        novalidate
    >
        @csrf

        @include('portal.personas.partials._form', [
            'persona' => null,
            'modoEdicion' => false,
        ])

    </form>

@endsection