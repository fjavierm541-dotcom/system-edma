@extends('layouts.portal')

@section('title', 'Nuevo horario | Portal EDMA')

@section('page-title', 'Nuevo horario')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Registrar horario</h1>

            <p>
                Configure una franja horaria que podrá utilizarse
                posteriormente al organizar los grupos.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.horarios.index') }}"
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
        action="{{ route('portal.horarios.store') }}"
        method="POST"
        novalidate
    >
        @csrf

        @include('portal.horarios.partials._form', [
            'horario' => null,
            'modoEdicion' => false,
        ])

    </form>

@endsection