@extends('layouts.portal')

@section('title', 'Editar persona | Portal EDMA')

@section('page-title', 'Editar persona')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de personas
            </span>

            <h1>Editar información personal</h1>

            <p>
                Actualice la información general de
                {{ $persona->nombre_completo }}.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.personas.show', $persona) }}"
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
        action="{{ route('portal.personas.update', $persona) }}"
        method="POST"
        enctype="multipart/form-data"
        id="personaForm"
        novalidate
    >
        @csrf
        @method('PUT')

        @include('portal.personas.partials._form', [
            'persona' => $persona,
            'modoEdicion' => true,
        ])

    </form>

@endsection