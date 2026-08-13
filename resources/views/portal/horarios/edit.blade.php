@extends('layouts.portal')

@section('title', 'Editar horario | Portal EDMA')

@section('page-title', 'Editar horario')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Editar horario</h1>

            <p>
                Actualice la franja horaria
                {{ $horario->nombre }}.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route(
                    'portal.horarios.show',
                    $horario
                ) }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver al horario
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route(
            'portal.horarios.update',
            $horario
        ) }}"
        method="POST"
        novalidate
    >
        @csrf
        @method('PUT')

        @include('portal.horarios.partials._form', [
            'horario' => $horario,
            'modoEdicion' => true,
        ])

    </form>

@endsection