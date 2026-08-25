@extends('layouts.portal')

@section(
    'title',
    'Inicio | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Portal Docente
            </span>

            <h1>
                Inicio
            </h1>

            <p>
                Bienvenido al espacio docente
                de Edumerican Academy.
            </p>

        </div>

    </div>

@endsection


@section('content')

    <section class="portal-card">

        <div class="p-4">

            <div
                class="
                    d-flex
                    align-items-center
                    gap-3
                "
            >

                <div class="portal-stat-icon">

                    <i
                        class="
                            bi
                            bi-person-workspace
                        "
                    ></i>

                </div>


                <div>

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Sesión docente
                    </span>

                    <h3 class="mb-1">

                        {{
                            $persona
                                ->nombre_completo
                        }}

                    </h3>

                    <p class="text-muted mb-0">
                        Tu acceso al Portal Docente
                        se encuentra habilitado.
                    </p>

                </div>

            </div>

        </div>

    </section>

@endsection