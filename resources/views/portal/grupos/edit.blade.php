@extends('layouts.portal')

@section('title', 'Editar grupo | Portal EDMA')

@section('page-title', 'Editar grupo')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Editar grupo académico</h1>

            <p>
                Actualice la configuración de
                {{ $grupo->etiqueta_completa }}.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route(
                    'portal.grupos.show',
                    $grupo
                ) }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver al grupo
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route(
            'portal.grupos.update',
            $grupo
        ) }}"
        method="POST"
        novalidate
    >
        @csrf
        @method('PUT')

        @include('portal.grupos.partials._form', [
            'grupo' => $grupo,
            'modoEdicion' => true,
        ])

    </form>

@endsection