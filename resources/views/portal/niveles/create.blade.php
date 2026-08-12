@extends('layouts.portal')

@section('title', 'Nuevo nivel | Portal EDMA')

@section('page-title', 'Nuevo nivel')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Registrar nivel académico</h1>

            <p>
                Configure un nivel dentro de uno de los
                programas académicos de EDMA.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.niveles.index') }}"
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
        action="{{ route('portal.niveles.store') }}"
        method="POST"
        novalidate
    >
        @csrf

        @include('portal.niveles.partials._form', [
            'nivel' => null,
            'modoEdicion' => false,
        ])

    </form>

@endsection