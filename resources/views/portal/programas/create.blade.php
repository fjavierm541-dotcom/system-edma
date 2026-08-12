@extends('layouts.portal')

@section('title', 'Nuevo programa | Portal EDMA')

@section('page-title', 'Nuevo programa')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Registrar programa académico</h1>

            <p>
                Configure un programa y el segmento de estudiantes
                al que estará dirigido.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.programas.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

        </div>

    </div>

@endsection

@section('content')

    <form
        action="{{ route('portal.programas.store') }}"
        method="POST"
        novalidate
    >
        @csrf

        @include('portal.programas.partials._form', [
            'modoEdicion' => false,
        ])

    </form>

@endsection