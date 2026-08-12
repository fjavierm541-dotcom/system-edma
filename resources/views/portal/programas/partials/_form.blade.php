@php
    $modoEdicion = $modoEdicion ?? false;
@endphp

<div class="row g-4">

    {{-- =====================================================
         Información general
         ===================================================== --}}
    <div class="col-12">

        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </div>

                <div>
                    <h2>Información del programa</h2>

                    <p>
                        Defina la identificación y clasificación
                        académica del programa.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    {{-- Código --}}
                    <div class="col-12 col-md-4">

                        <label
                            for="codigo"
                            class="form-label portal-form-label"
                        >
                            Código
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="codigo"
                            id="codigo"
                            value="{{ old(
                                'codigo',
                                $programa->codigo ?? ''
                            ) }}"
                            class="form-control portal-form-control
                                @error('codigo') is-invalid @enderror"
                            maxlength="20"
                            autocomplete="off"
                            placeholder="Ej. ING-NIN"
                            required
                        >

                        @error('codigo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Identificador corto y único del programa.
                        </div>

                    </div>

                    {{-- Nombre --}}
                    <div class="col-12 col-md-8">

                        <label
                            for="nombre"
                            class="form-label portal-form-label"
                        >
                            Nombre del programa
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old(
                                'nombre',
                                $programa->nombre ?? ''
                            ) }}"
                            class="form-control portal-form-control
                                @error('nombre') is-invalid @enderror"
                            maxlength="150"
                            placeholder="Ej. Diplomado de Inglés para Niños"
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Segmento --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="segmento"
                            class="form-label portal-form-label"
                        >
                            Segmento
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="segmento"
                            id="segmento"
                            class="form-select portal-form-control
                                @error('segmento') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Seleccione un segmento
                            </option>

                            <option
                                value="niños"
                                @selected(
                                    old(
                                        'segmento',
                                        $programa->segmento ?? ''
                                    ) === 'niños'
                                )
                            >
                                Niños
                            </option>

                            <option
                                value="jóvenes_adultos"
                                @selected(
                                    old(
                                        'segmento',
                                        $programa->segmento ?? ''
                                    ) === 'jóvenes_adultos'
                                )
                            >
                                Jóvenes y adultos
                            </option>

                        </select>

                        @error('segmento')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Niños: 7 a 13 años. Jóvenes y adultos:
                            14 años en adelante.
                        </div>

                    </div>

                    {{-- Estado --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="estado"
                            class="form-label portal-form-label"
                        >
                            Estado
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="estado"
                            id="estado"
                            class="form-select portal-form-control
                                @error('estado') is-invalid @enderror"
                            required
                        >
                            <option
                                value="activo"
                                @selected(
                                    old(
                                        'estado',
                                        $programa->estado ?? 'activo'
                                    ) === 'activo'
                                )
                            >
                                Activo
                            </option>

                            <option
                                value="inactivo"
                                @selected(
                                    old(
                                        'estado',
                                        $programa->estado ?? 'activo'
                                    ) === 'inactivo'
                                )
                            >
                                Inactivo
                            </option>

                        </select>

                        @error('estado')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Los programas inactivos conservarán su
                            historial, pero no deberán ofrecerse para
                            nuevas operaciones académicas.
                        </div>

                    </div>

                    {{-- Descripción --}}
                    <div class="col-12">

                        <label
                            for="descripcion"
                            class="form-label portal-form-label"
                        >
                            Descripción
                        </label>

                        <textarea
                            name="descripcion"
                            id="descripcion"
                            rows="4"
                            maxlength="2000"
                            class="form-control portal-form-control
                                @error('descripcion') is-invalid @enderror"
                            placeholder="Descripción general del programa académico..."
                        >{{ old(
                            'descripcion',
                            $programa->descripcion ?? ''
                        ) }}</textarea>

                        @error('descripcion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-text-counter">

                            <span>
                                Campo opcional.
                            </span>

                            <span>
                                <strong id="descripcionCounter">0</strong>
                                / 2000
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

    {{-- =====================================================
         Información del segmento
         ===================================================== --}}
    <div class="col-12">

        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-people"></i>
                </div>

                <div>
                    <h2>Clasificación de estudiantes</h2>

                    <p>
                        Referencia institucional para la clasificación
                        del programa según la edad.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12 col-lg-6">

                        <div class="portal-detail-item h-100">

                            <span>
                                Programa para niños
                            </span>

                            <strong>
                                7 a 13 años
                            </strong>

                            <small>
                                Corresponde al segmento académico
                                identificado como Niños.
                            </small>

                        </div>

                    </div>

                    <div class="col-12 col-lg-6">

                        <div class="portal-detail-item h-100">

                            <span>
                                Programa para jóvenes y adultos
                            </span>

                            <strong>
                                14 años en adelante
                            </strong>

                            <small>
                                Incluye tanto estudiantes jóvenes como
                                estudiantes adultos.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

    {{-- =====================================================
         Información importante
         ===================================================== --}}
    <div class="col-12">

        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>
                    <h2>Información importante</h2>

                    <p>
                        Reglas generales aplicables al programa.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <ul class="portal-form-guidelines mb-0">

                    <li>
                        <i class="bi bi-check-circle"></i>

                        <span>
                            Cada programa pertenece a un único segmento académico.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle"></i>

                        <span>
                            Los niveles académicos se configurarán posteriormente
                            dentro de cada programa.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle"></i>

                        <span>
                            La edad del estudiante permitirá validar el segmento
                            correspondiente durante la inscripción y matrícula.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle"></i>

                        <span>
                            Desactivar un programa no elimina su historial ni
                            los niveles previamente asociados.
                        </span>
                    </li>

                </ul>

            </div>

        </section>

    </div>

    {{-- =====================================================
         Acciones
         ===================================================== --}}
    <div class="col-12">

        <section class="portal-card portal-form-actions-card">

            <div class="portal-form-actions">

                <a
                    href="{{ $modoEdicion
                        ? route(
                            'portal.programas.show',
                            $programa
                        )
                        : route('portal.programas.index') }}"
                    class="btn portal-btn-secondary"
                >
                    <i class="bi bi-x-circle"></i>
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn portal-btn-primary"
                >
                    <i class="bi bi-check2-circle"></i>

                    {{ $modoEdicion
                        ? 'Guardar cambios'
                        : 'Registrar programa' }}
                </button>

            </div>

        </section>

    </div>

</div>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const codeInput = document.getElementById(
            'codigo'
        );

        const description = document.getElementById(
            'descripcion'
        );

        const descriptionCounter = document.getElementById(
            'descripcionCounter'
        );

        const updateDescriptionCounter = () => {
            if (
                !description ||
                !descriptionCounter
            ) {
                return;
            }

            descriptionCounter.textContent =
                description.value.length;
        };

        codeInput?.addEventListener('input', () => {
            codeInput.value = codeInput.value
                .toUpperCase()
                .replace(/[^A-Z0-9_-]/g, '')
                .slice(0, 20);
        });

        description?.addEventListener(
            'input',
            updateDescriptionCounter
        );

        updateDescriptionCounter();
    });
</script>

@endpush