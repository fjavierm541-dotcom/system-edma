@extends('layouts.portal')

@section('title', 'Mi matrícula | Portal EDMA')

@section('page-header')
    <div>
        <h1 class="mb-1">
            Mi matrícula
        </h1>

        <p class="text-muted mb-0">
            Consulta las opciones disponibles para continuar tu formación en EDMA.
        </p>
    </div>
@endsection

@section('content')

    @if ($mensajeBloqueo)

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>

            {{ $mensajeBloqueo }}
        </div>

    @else

        <div class="row g-4">

            <div class="col-12 col-lg-4">

                <div class="card h-100">
                    <div class="card-body">

                        <h5 class="card-title">
                            Tu información académica
                        </h5>

                        <p class="mb-2">
                            <strong>Estudiante:</strong><br>
                            {{ $estudiante->persona->nombre_completo }}
                        </p>

                        <p class="mb-2">
                            <strong>Código EDMA:</strong><br>
                            {{ $estudiante->codigo_estudiante }}
                        </p>

                        <p class="mb-0">
                            <strong>Nivel autorizado:</strong><br>
                            {{ $estudiante->nivelAutorizado->nombre }}
                        </p>

                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-8">

                <div class="card h-100">
                    <div class="card-body">

                        <h5 class="card-title">
                            Período disponible
                        </h5>

                        <p class="mb-2">
                            <strong>
                                {{ $periodo->nombre }}
                            </strong>
                        </p>

                        <p class="text-muted mb-0">
                            Puedes seleccionar uno de los grupos disponibles para tu nivel.
                        </p>

                    </div>
                </div>

            </div>

        </div>

        <div class="mt-4">

            <h4>
                Grupos disponibles
            </h4>

            <div class="row g-3 mt-1">

                @foreach ($grupos as $grupo)

                    <div class="col-12 col-md-6 col-xl-4">

                        <div class="card h-100">
                            <div class="card-body">

                                <h5>
                                    {{ $grupo->nombre }}
                                </h5>

                                <p class="mb-2">
                                    <strong>Horario:</strong>

                                    @forelse ($grupo->horarios as $horario)
                                        {{ $horario->nombre }}
                                        @unless ($loop->last)
                                            <br>
                                        @endunless
                                    @empty
                                        Por definir
                                    @endforelse
                                </p>

                                <p class="mb-2">
                                    <strong>Cupos disponibles:</strong>

                                    {{
                                        $grupo->cupo_maximo
                                        - $grupo->matriculas_activas_count
                                    }}
                                </p>

                                <button
                                    type="button"
                                    class="btn btn-primary w-100"
                                    disabled
                                >
                                    Seleccionar grupo
                                </button>

                            </div>
                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif

@endsection