@extends('layouts.portal')

@section('title', 'Nuevo grupo | Portal EDMA')

@section('page-title', 'Nuevo grupo')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Registrar grupo académico</h1>

            <p>
                Configure el grupo, su nivel, período académico
                y fechas de desarrollo.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.grupos.index') }}"
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
        action="{{ route('portal.grupos.store') }}"
        method="POST"
        novalidate
    >
        @csrf

        @include('portal.grupos.partials._form', [
            'grupo' => null,
            'modoEdicion' => false,
        ])

    </form>

@endsection