@php
    $modoEdicion = $modoEdicion ?? false;

    $programaActual = old(
        'programa_id',
        $programaSeleccionado ?? ''
    );

    $nivelActual = old(
        'nivel_id',
        $nivelSeleccionado ?? ''
    );

    $periodoActual = old(
        'periodo_academico_id',
        $periodoSeleccionado ?? ''
    );

    $fechaInicioActual = old(
        'fecha_inicio',
        $grupo?->fecha_inicio?->format('Y-m-d')
    );

    $fechaFinActual = old(
        'fecha_fin',
        $grupo?->fecha_fin?->format('Y-m-d')
    );

    $estadoActual = old(
        'estado',
        $grupo->estado ?? 'activo'
    );
@endphp

<div class="row g-4">

    {{-- =====================================================
         Información del grupo
         ===================================================== --}}
    <div class="col-12 col-xl-8">

        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-people"></i>
                </div>

                <div>
                    <h2>Información del grupo</h2>

                    <p>
                        Seleccione el programa, nivel y período
                        académico al que pertenecerá el grupo.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    {{-- Programa --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="programa_id"
                            class="form-label portal-form-label"
                        >
                            Programa
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="programa_id"
                            id="programa_id"
                            class="form-select portal-form-control"
                            required
                        >
                            <option value="">
                                Seleccione un programa
                            </option>

                            @foreach ($programas as $programa)

                                <option
                                    value="{{ $programa->id }}"
                                    @selected(
                                        (string) $programaActual
                                        === (string) $programa->id
                                    )
                                >
                                    {{ $programa->nombre }}
                                </option>

                            @endforeach

                        </select>

                        <div class="portal-form-help">
                            Primero seleccione el programa para
                            identificar sus niveles disponibles.
                        </div>

                    </div>

                    {{-- Nivel --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="nivel_id"
                            class="form-label portal-form-label"
                        >
                            Nivel
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="nivel_id"
                            id="nivel_id"
                            class="form-select portal-form-control
                                @error('nivel_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Seleccione un nivel
                            </option>

                            @foreach ($niveles as $nivel)

                                <option
                                    value="{{ $nivel->id }}"
                                    data-programa="{{ $nivel->programa_id }}"
                                    @selected(
                                        (string) $nivelActual
                                        === (string) $nivel->id
                                    )
                                >
                                    {{ $nivel->nombre }}
                                </option>

                            @endforeach

                        </select>

                        @error('nivel_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Período --}}
                    <div class="col-12">

                        <label
                            for="periodo_academico_id"
                            class="form-label portal-form-label"
                        >
                            Período académico
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="periodo_academico_id"
                            id="periodo_academico_id"
                            class="form-select portal-form-control
                                @error('periodo_academico_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Seleccione un período
                            </option>

                            @foreach ($periodos as $periodo)

                                <option
                                    value="{{ $periodo->id }}"
                                    data-inicio="{{ $periodo->fecha_inicio?->format('Y-m-d') }}"
                                    data-fin="{{ $periodo->fecha_fin?->format('Y-m-d') }}"
                                    @selected(
                                        (string) $periodoActual
                                        === (string) $periodo->id
                                    )
                                >
                                    {{ $periodo->nombre }}
                                    — {{ $periodo->codigo }}
                                </option>

                            @endforeach

                        </select>

                        @error('periodo_academico_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            El grupo deberá desarrollar sus clases
                            dentro de las fechas del período seleccionado.
                        </div>

                    </div>

                    {{-- Nombre --}}
                    <div class="col-12">

                        <label
                            for="nombre"
                            class="form-label portal-form-label"
                        >
                            Nombre del grupo
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old(
                                'nombre',
                                $grupo->nombre ?? ''
                            ) }}"
                            class="form-control portal-form-control
                                @error('nombre') is-invalid @enderror"
                            maxlength="120"
                            placeholder="Ej. Grupo A"
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Utilice un nombre sencillo. El sistema mostrará
                            automáticamente el nivel, segmento y período
                            cuando sea necesario identificar el grupo.
                        </div>

                    </div>

                    {{-- Fechas --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="fecha_inicio"
                            class="form-label portal-form-label"
                        >
                            Fecha de inicio
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="date"
                            name="fecha_inicio"
                            id="fecha_inicio"
                            value="{{ $fechaInicioActual }}"
                            class="form-control portal-form-control
                                @error('fecha_inicio') is-invalid @enderror"
                            required
                        >

                        @error('fecha_inicio')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="fecha_fin"
                            class="form-label portal-form-label"
                        >
                            Fecha de finalización
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="date"
                            name="fecha_fin"
                            id="fecha_fin"
                            value="{{ $fechaFinActual }}"
                            class="form-control portal-form-control
                                @error('fecha_fin') is-invalid @enderror"
                            required
                        >

                        @error('fecha_fin')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Observaciones --}}
                    <div class="col-12">

                        <label
                            for="observaciones"
                            class="form-label portal-form-label"
                        >
                            Observaciones
                        </label>

                        <textarea
                            name="observaciones"
                            id="observaciones"
                            rows="4"
                            maxlength="2000"
                            class="form-control portal-form-control
                                @error('observaciones') is-invalid @enderror"
                        >{{ old(
                            'observaciones',
                            $grupo->observaciones ?? ''
                        ) }}</textarea>

                        @error('observaciones')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </section>

    </div>

    {{-- =====================================================
         Columna lateral
         ===================================================== --}}
    <div class="col-12 col-xl-4">

        <div class="portal-form-sidebar">

            {{-- Código --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-upc-scan"></i>
                    </div>

                    <div>
                        <h2>Identificación</h2>

                        <p>
                            Código institucional del grupo.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    @if ($modoEdicion)

                        <div class="portal-student-code-panel">

                            <span>Código del grupo</span>

                            <strong>
                                {{ $grupo->codigo }}
                            </strong>

                            <small>
                                Este código no puede modificarse.
                            </small>

                        </div>

                    @else

                        <div class="portal-student-code-panel portal-student-code-pending">

                            <span>Código del grupo</span>

                            <strong>
                                Se asignará automáticamente
                            </strong>

                            <small>
                                Se generará al registrar el grupo.
                            </small>

                        </div>

                    @endif

                </div>

            </section>

            {{-- Configuración --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-sliders"></i>
                    </div>

                    <div>
                        <h2>Configuración</h2>

                        <p>
                            Condiciones actuales del grupo.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <div class="portal-detail-grid">

                        <div class="portal-detail-item portal-detail-item-full">

                            <span>Modalidad</span>

                            <strong>
                                Virtual
                            </strong>

                        </div>

                        <div class="portal-detail-item">

                            <span>Mínimo</span>

                            <strong>
                                3 estudiantes
                            </strong>

                        </div>

                        <div class="portal-detail-item">

                            <span>Máximo</span>

                            <strong>
                                25 estudiantes
                            </strong>

                        </div>

                    </div>

                    <div class="mt-3">

                        <label
                            for="estado"
                            class="form-label portal-form-label"
                        >
                            Estado
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
                                    $estadoActual === 'activo'
                                )
                            >
                                Activo
                            </option>

                            <option
                                value="inactivo"
                                @selected(
                                    $estadoActual === 'inactivo'
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

                    </div>

                </div>

            </section>

            {{-- Acciones --}}
            <section class="portal-card portal-form-actions-card">

                <div class="portal-form-actions">

                    <button
                        type="submit"
                        class="btn portal-btn-primary w-100"
                    >
                        <i class="bi bi-check2-circle"></i>

                        {{ $modoEdicion
                            ? 'Guardar cambios'
                            : 'Registrar grupo' }}
                    </button>

                    <a
                        href="{{ $modoEdicion
                            ? route(
                                'portal.grupos.show',
                                $grupo
                            )
                            : route('portal.grupos.index') }}"
                        class="btn portal-btn-secondary w-100"
                    >
                        Cancelar
                    </a>

                </div>

            </section>

        </div>

    </div>

</div>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const programaSelect =
            document.getElementById(
                'programa_id'
            );

        const nivelSelect =
            document.getElementById(
                'nivel_id'
            );

        const periodoSelect =
            document.getElementById(
                'periodo_academico_id'
            );

        const fechaInicio =
            document.getElementById(
                'fecha_inicio'
            );

        const fechaFin =
            document.getElementById(
                'fecha_fin'
            );

        const filterLevels = () => {
            if (
                !programaSelect ||
                !nivelSelect
            ) {
                return;
            }

            const programaId =
                programaSelect.value;

            Array.from(
                nivelSelect.options
            ).forEach(option => {

                if (!option.value) {
                    return;
                }

                const visible =
                    !programaId ||
                    option.dataset.programa
                    === programaId;

                option.hidden = !visible;
                option.disabled = !visible;
            });

            const selected =
                nivelSelect.options[
                    nivelSelect.selectedIndex
                ];

            if (
                selected &&
                selected.value &&
                selected.disabled
            ) {
                nivelSelect.value = '';
            }
        };

        const updatePeriodDates = () => {
            if (!periodoSelect) {
                return;
            }

            const option =
                periodoSelect.options[
                    periodoSelect.selectedIndex
                ];

            if (!option || !option.value) {
                return;
            }

            const inicio =
                option.dataset.inicio || '';

            const fin =
                option.dataset.fin || '';

            if (fechaInicio) {
                fechaInicio.min = inicio;
                fechaInicio.max = fin;
            }

            if (fechaFin) {
                fechaFin.min =
                    fechaInicio?.value ||
                    inicio;

                fechaFin.max = fin;
            }
        };

        programaSelect?.addEventListener(
            'change',
            filterLevels
        );

        periodoSelect?.addEventListener(
            'change',
            updatePeriodDates
        );

        fechaInicio?.addEventListener(
            'change',
            () => {
                updatePeriodDates();

                if (fechaFin) {
                    fechaFin.min =
                        fechaInicio.value ||
                        fechaFin.min;
                }
            }
        );

        filterLevels();
        updatePeriodDates();
    });
</script>

@endpush