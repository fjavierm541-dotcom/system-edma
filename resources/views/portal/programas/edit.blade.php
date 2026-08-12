@extends('layouts.portal')

@section('title', 'Editar programa | Portal EDMA')

@section('page-title', 'Editar programa')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Editar programa académico</h1>

            <p>
                Actualice la información de
                {{ $programa->nombre }}.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route(
                    'portal.programas.show',
                    $programa
                ) }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver al programa
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route(
            'portal.programas.update',
            $programa
        ) }}"
        method="POST"
        novalidate
    >
        @csrf
        @method('PUT')

        @include('portal.programas.partials._form', [
            'programa' => $programa,
            'modoEdicion' => true,
        ])

    </form>

@endsection