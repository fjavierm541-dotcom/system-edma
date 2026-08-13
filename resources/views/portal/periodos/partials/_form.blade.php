@php
    $modoEdicion = $modoEdicion ?? false;

    $fechaInicio = old(
        'fecha_inicio',
        $periodo?->fecha_inicio?->format('Y-m-d')
    );

    $fechaFin = old(
        'fecha_fin',
        $periodo?->fecha_fin?->format('Y-m-d')
    );

    $fechaInicioMatricula = old(
        'fecha_inicio_matricula',
        $periodo?->fecha_inicio_matricula?->format('Y-m-d')
    );

    $fechaFinMatricula = old(
        'fecha_fin_matricula',
        $periodo?->fecha_fin_matricula?->format('Y-m-d')
    );

    $estadoActual = old(
        'estado',
        $periodo->estado ?? 'activo'
    );
@endphp

<div class="row g-4">

    <div class="col-12 col-xl-8">

        {{-- Información general --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <h2>Información del período</h2>

                    <p>
                        Identifique el período académico que se utilizará
                        para organizar grupos, matrículas y actividades.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12">

                        <label
                            for="nombre"
                            class="form-label portal-form-label"
                        >
                            Nombre del período
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old(
                                'nombre',
                                $periodo->nombre ?? ''
                            ) }}"
                            class="form-control portal-form-control
                                @error('nombre') is-invalid @enderror"
                            maxlength="100"
                            placeholder="Ej. Tercer período académico 2026"
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            El código institucional será asignado automáticamente
                            al registrar el período.
                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- Fechas de matrícula --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-person-check"></i>
                </div>

                <div>
                    <h2>Período de matrícula</h2>

                    <p>
                        Defina las fechas durante las cuales se permitirá
                        realizar matrículas para este período.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12 col-md-6">

                        <label
                            for="fecha_inicio_matricula"
                            class="form-label portal-form-label"
                        >
                            Inicio de matrícula
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="date"
                            name="fecha_inicio_matricula"
                            id="fecha_inicio_matricula"
                            value="{{ $fechaInicioMatricula }}"
                            class="form-control portal-form-control
                                @error('fecha_inicio_matricula') is-invalid @enderror"
                            required
                        >

                        @error('fecha_inicio_matricula')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="fecha_fin_matricula"
                            class="form-label portal-form-label"
                        >
                            Cierre de matrícula
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="date"
                            name="fecha_fin_matricula"
                            id="fecha_fin_matricula"
                            value="{{ $fechaFinMatricula }}"
                            class="form-control portal-form-control
                                @error('fecha_fin_matricula') is-invalid @enderror"
                            required
                        >

                        @error('fecha_fin_matricula')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12">

                        <div class="portal-inline-notice">

                            <i class="bi bi-info-circle"></i>

                            <div>
                                <strong>Ventana de matrícula</strong>

                                <span>
                                    Fuera de estas fechas, el período no se
                                    considerará abierto para nuevas matrículas.
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- Fechas académicas --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-calendar-range"></i>
                </div>

                <div>
                    <h2>Desarrollo académico</h2>

                    <p>
                        Indique cuándo comienzan y finalizan las actividades
                        académicas correspondientes al período.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12 col-md-6">

                        <label
                            for="fecha_inicio"
                            class="form-label portal-form-label"
                        >
                            Inicio del período
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="date"
                            name="fecha_inicio"
                            id="fecha_inicio"
                            value="{{ $fechaInicio }}"
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
                            Finalización del período
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="date"
                            name="fecha_fin"
                            id="fecha_fin"
                            value="{{ $fechaFin }}"
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

                    <div class="col-12">

                        <div class="portal-inline-notice">

                            <i class="bi bi-calendar-check"></i>

                            <div>
                                <strong>Fechas académicas</strong>

                                <span>
                                    Los grupos creados para este período podrán
                                    organizarse dentro de estas fechas.
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- Observaciones --}}
        <section class="portal-card portal-form-card mb-0">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-journal-text"></i>
                </div>

                <div>
                    <h2>Observaciones</h2>

                    <p>
                        Registre cualquier información adicional
                        que deba considerarse durante este período.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <textarea
                    name="observaciones"
                    id="observaciones"
                    rows="5"
                    maxlength="2000"
                    class="form-control portal-form-control
                        @error('observaciones') is-invalid @enderror"
                >{{ old(
                    'observaciones',
                    $periodo->observaciones ?? ''
                ) }}</textarea>

                @error('observaciones')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <div class="portal-form-text-counter">

                    <span>
                        Campo opcional.
                    </span>

                    <span>
                        <strong id="observacionesCounter">0</strong>
                        / 2000
                    </span>

                </div>

            </div>

        </section>

    </div>

    {{-- Columna lateral --}}
    <div class="col-12 col-xl-4">

        <div class="portal-form-sidebar">

            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-toggle-on"></i>
                    </div>

                    <div>
                        <h2>Estado</h2>

                        <p>
                            Disponibilidad del período académico.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <label
                        for="estado"
                        class="form-label portal-form-label"
                    >
                        Estado del período
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
                            @selected($estadoActual === 'activo')
                        >
                            Activo
                        </option>

                        <option
                            value="inactivo"
                            @selected($estadoActual === 'inactivo')
                        >
                            Inactivo
                        </option>

                    </select>

                    @error('estado')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="portal-form-help mt-2">
                        Un período inactivo conservará todos los
                        grupos y registros asociados.
                    </div>

                </div>

            </section>

            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>

                    <div>
                        <h2>Antes de guardar</h2>

                        <p>
                            Revise estos datos para evitar inconsistencias.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <ul class="portal-form-guidelines mb-0">

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Verifique que el período de matrícula
                                tenga una fecha de inicio y cierre correcta.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Confirme que las fechas académicas coincidan
                                con la planificación establecida.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Los grupos deberán asociarse posteriormente
                                al período correspondiente.
                            </span>
                        </li>

                    </ul>

                </div>

            </section>

            <section class="portal-card portal-form-actions-card">

                <div class="portal-form-actions">

                    <button
                        type="submit"
                        class="btn portal-btn-primary w-100"
                    >
                        <i class="bi bi-check2-circle"></i>

                        {{ $modoEdicion
                            ? 'Guardar cambios'
                            : 'Registrar período' }}
                    </button>

                    <a
                        href="{{ $modoEdicion
                            ? route(
                                'portal.periodos.show',
                                $periodo
                            )
                            : route('portal.periodos.index') }}"
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
    
        const enrollmentStart = document.getElementById(
            'fecha_inicio_matricula'
        );

        const enrollmentEnd = document.getElementById(
            'fecha_fin_matricula'
        );

        const academicStart = document.getElementById(
            'fecha_inicio'
        );

        const academicEnd = document.getElementById(
            'fecha_fin'
        );

        const observations = document.getElementById(
            'observaciones'
        );

        const observationsCounter = document.getElementById(
            'observacionesCounter'
        );

       

        const updateDates = () => {
            if (enrollmentStart && enrollmentEnd) {
                enrollmentEnd.min =
                    enrollmentStart.value || '';
            }

            if (academicStart && academicEnd) {
                academicEnd.min =
                    academicStart.value || '';
            }

            if (academicEnd && enrollmentEnd) {
                enrollmentEnd.max =
                    academicEnd.value || '';
            }
        };

        enrollmentStart?.addEventListener(
            'change',
            updateDates
        );

        academicStart?.addEventListener(
            'change',
            updateDates
        );

        academicEnd?.addEventListener(
            'change',
            updateDates
        );

        const updateObservationsCounter = () => {
            if (!observations || !observationsCounter) {
                return;
            }

            observationsCounter.textContent =
                observations.value.length;
        };

        observations?.addEventListener(
            'input',
            updateObservationsCounter
        );

        updateDates();
        updateObservationsCounter();
    });
</script>

@endpush