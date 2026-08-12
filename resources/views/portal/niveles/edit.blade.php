@extends('layouts.portal')

@section('title', 'Editar nivel | Portal EDMA')

@section('page-title', 'Editar nivel')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Editar nivel académico</h1>

            <p>
                Actualice la configuración del nivel
                {{ $nivel->nombre }}.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route(
                    'portal.niveles.show',
                    $nivel
                ) }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver al nivel
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route(
            'portal.niveles.update',
            $nivel
        ) }}"
        method="POST"
        novalidate
    >
        @csrf
        @method('PUT')

        @include('portal.niveles.partials._form', [
            'nivel' => $nivel,
            'modoEdicion' => true,
        ])

    </form>

@endsection