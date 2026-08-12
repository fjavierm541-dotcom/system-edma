@php
    $empleadoActual = $modoEdicion
        ? $docente->empleado
        : $empleadoSeleccionado;

    $empleadoIdActual = old(
        'empleado_id',
        $modoEdicion
            ? $docente->empleado_id
            : $empleadoSeleccionado?->id
    );

    $fechaInicioActual = old(
        'fecha_inicio_docencia',
        $docente?->fecha_inicio_docencia?->format('Y-m-d')
            ?? now()->format('Y-m-d')
    );

    $estadoActual = old(
        'estado',
        $docente?->estado ?? 'activo'
    );
@endphp

<div class="row g-4">

    <div class="col-12 col-xl-8">

        {{-- Empleado --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-briefcase"></i>
                </div>

                <div>
                    <h2>Empleado asociado</h2>

                    <p>
                        El perfil docente se vincula a un empleado existente.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                @if ($modoEdicion)

                    <div class="portal-student-person-card">

                        <div class="portal-student-person-avatar">

                            @if ($empleadoActual?->persona?->foto_perfil)

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        $empleadoActual->persona->foto_perfil
                                    ) }}"
                                    alt="Fotografía de {{ $empleadoActual->persona->nombre_completo }}"
                                >

                            @else

                                <span>
                                    {{ $empleadoActual?->persona?->iniciales ?: 'DO' }}
                                </span>

                            @endif

                        </div>

                        <div class="portal-student-person-info">

                            <span class="portal-student-person-label">
                                Empleado vinculado
                            </span>

                            <h3>
                                {{ $empleadoActual->persona->nombre_completo }}
                            </h3>

                            <div class="portal-student-person-meta">

                                <span>
                                    <i class="bi bi-upc-scan"></i>
                                    {{ $empleadoActual->codigo_empleado }}
                                </span>

                                @if ($empleadoActual->persona->numero_documento)

                                    <span>
                                        <i class="bi bi-credit-card-2-front"></i>
                                        {{ $empleadoActual->persona->numero_documento }}
                                    </span>

                                @endif

                                @if ($empleadoActual->persona->correo_personal)

                                    <span>
                                        <i class="bi bi-envelope"></i>
                                        {{ $empleadoActual->persona->correo_personal }}
                                    </span>

                                @endif

                            </div>

                        </div>

                        <a
                            href="{{ route(
                                'portal.empleados.show',
                                $empleadoActual
                            ) }}"
                            class="btn portal-btn-secondary btn-sm"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <i class="bi bi-box-arrow-up-right"></i>
                            Ver empleado
                        </a>

                    </div>

                    <div class="portal-form-help mt-3">
                        El empleado vinculado y el código docente
                        no pueden cambiarse después de crear el perfil.
                    </div>

                @else

                    <div class="mb-3">

                        <label
                            for="empleado_id"
                            class="form-label portal-form-label"
                        >
                            Seleccione un empleado
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="empleado_id"
                            id="empleado_id"
                            class="form-select portal-form-control
                                @error('empleado_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Seleccione un empleado disponible
                            </option>

                            @foreach ($empleadosDisponibles as $empleado)

                                @php
                                    $personaEmpleado = $empleado->persona;
                                @endphp

                                <option
                                    value="{{ $empleado->id }}"
                                    data-name="{{ $personaEmpleado?->nombre_completo }}"
                                    data-employee-code="{{ $empleado->codigo_empleado }}"
                                    data-document="{{ $personaEmpleado?->numero_documento }}"
                                    data-email="{{ $personaEmpleado?->correo_personal }}"
                                    data-phone="{{ $personaEmpleado?->telefono_movil }}"
                                    data-photo="{{ $personaEmpleado?->foto_perfil
                                        ? asset('storage/' . $personaEmpleado->foto_perfil)
                                        : '' }}"
                                    data-initials="{{ $personaEmpleado?->iniciales }}"
                                    @selected(
                                        (string) $empleadoIdActual
                                        === (string) $empleado->id
                                    )
                                >
                                    {{ $personaEmpleado?->nombre_completo }}
                                    — {{ $empleado->codigo_empleado }}
                                </option>

                            @endforeach

                        </select>

                        @error('empleado_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Solo aparecen empleados activos que aún no poseen
                            perfil docente.
                        </div>

                    </div>

                    @if ($empleadosDisponibles->isEmpty())

                        <div class="portal-inline-notice portal-inline-notice-warning">

                            <i class="bi bi-exclamation-triangle"></i>

                            <div>
                                <strong>No hay empleados disponibles</strong>

                                <span>
                                    Todos los empleados activos ya poseen perfil
                                    docente o no existen empleados activos disponibles.
                                </span>
                            </div>

                            <a
                                href="{{ route('portal.empleados.create') }}"
                                class="btn portal-btn-secondary btn-sm"
                            >
                                Nuevo empleado
                            </a>

                        </div>

                    @endif

                    <div
                        class="portal-student-person-card"
                        id="selectedEmployeeCard"
                        @if (!$empleadoSeleccionado) hidden @endif
                    >

                        <div class="portal-student-person-avatar">

                            <img
                                src="{{ $empleadoSeleccionado?->persona?->foto_perfil
                                    ? asset(
                                        'storage/' .
                                        $empleadoSeleccionado->persona->foto_perfil
                                    )
                                    : '' }}"
                                alt="Fotografía del empleado"
                                id="selectedEmployeeImage"
                                @if (!$empleadoSeleccionado?->persona?->foto_perfil) hidden @endif
                            >

                            <span
                                id="selectedEmployeeInitials"
                                @if ($empleadoSeleccionado?->persona?->foto_perfil) hidden @endif
                            >
                                {{ $empleadoSeleccionado?->persona?->iniciales ?: 'DO' }}
                            </span>

                        </div>

                        <div class="portal-student-person-info">

                            <span class="portal-student-person-label">
                                Empleado seleccionado
                            </span>

                            <h3 id="selectedEmployeeName">
                                {{ $empleadoSeleccionado?->persona?->nombre_completo }}
                            </h3>

                            <div class="portal-student-person-meta">

                                <span id="selectedEmployeeCodeWrapper">
                                    <i class="bi bi-upc-scan"></i>

                                    <span id="selectedEmployeeCode">
                                        {{ $empleadoSeleccionado?->codigo_empleado }}
                                    </span>
                                </span>

                                <span id="selectedEmployeeDocumentWrapper">
                                    <i class="bi bi-credit-card-2-front"></i>

                                    <span id="selectedEmployeeDocument">
                                        {{ $empleadoSeleccionado?->persona?->numero_documento }}
                                    </span>
                                </span>

                                <span id="selectedEmployeeEmailWrapper">
                                    <i class="bi bi-envelope"></i>

                                    <span id="selectedEmployeeEmail">
                                        {{ $empleadoSeleccionado?->persona?->correo_personal }}
                                    </span>
                                </span>

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </section>

        {{-- Información docente --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-easel"></i>
                </div>

                <div>
                    <h2>Información docente</h2>

                    <p>
                        Datos específicos de su función académica.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12">

                        <label
                            for="especialidad"
                            class="form-label portal-form-label"
                        >
                            Especialidad
                        </label>

                        <input
                            type="text"
                            name="especialidad"
                            id="especialidad"
                            value="{{ old(
                                'especialidad',
                                $docente?->especialidad
                            ) }}"
                            class="form-control portal-form-control
                                @error('especialidad') is-invalid @enderror"
                            maxlength="180"
                            placeholder="Ej. Enseñanza del idioma inglés"
                        >

                        @error('especialidad')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="fecha_inicio_docencia"
                            class="form-label portal-form-label"
                        >
                            Fecha de inicio de docencia
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="date"
                            name="fecha_inicio_docencia"
                            id="fecha_inicio_docencia"
                            value="{{ $fechaInicioActual }}"
                            max="{{ now()->format('Y-m-d') }}"
                            class="form-control portal-form-control
                                @error('fecha_inicio_docencia') is-invalid @enderror"
                            required
                        >

                        @error('fecha_inicio_docencia')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="estado"
                            class="form-label portal-form-label"
                        >
                            Estado docente
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

                    </div>

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
                            rows="5"
                            maxlength="1000"
                            class="form-control portal-form-control
                                @error('observaciones') is-invalid @enderror"
                        >{{ old(
                            'observaciones',
                            $docente?->observaciones
                        ) }}</textarea>

                        <div class="portal-form-text-counter">

                            <span>
                                Información académica o administrativa opcional.
                            </span>

                            <span>
                                <strong id="observacionesCounter">0</strong>
                                / 1000
                            </span>

                        </div>

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

    {{-- Columna lateral --}}
    <div class="col-12 col-xl-4">

        <div class="portal-form-sidebar">

            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-upc-scan"></i>
                    </div>

                    <div>
                        <h2>Código docente</h2>

                        <p>
                            Identificador institucional del docente.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    @if ($modoEdicion)

                        <div class="portal-student-code-panel">

                            <span>Código docente</span>

                            <strong>
                                {{ $docente->codigo_docente }}
                            </strong>

                            <small>
                                Este código es único e inmutable.
                            </small>

                        </div>

                    @else

                        <div class="portal-student-code-panel portal-student-code-pending">

                            <span>Próximo código</span>

                            <strong>
                                Se generará automáticamente
                            </strong>

                            <small>
                                El correlativo será asignado al guardar.
                            </small>

                        </div>

                    @endif

                </div>

            </section>

            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h2>Información importante</h2>
                        <p>Reglas del perfil docente.</p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <ul class="portal-form-guidelines">

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Solo un empleado activo puede convertirse
                                en docente.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Cada empleado puede tener un único perfil
                                docente.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Los datos personales y laborales permanecen
                                en Personas y Empleados.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                El código EDMA-DOC se genera automáticamente
                                y no puede modificarse.
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
                        @disabled(
                            !$modoEdicion &&
                            $empleadosDisponibles->isEmpty()
                        )
                    >
                        <i class="bi bi-check2-circle"></i>

                        {{ $modoEdicion
                            ? 'Guardar cambios'
                            : 'Crear perfil docente' }}
                    </button>

                    <a
                        href="{{ $modoEdicion
                            ? route(
                                'portal.docentes.show',
                                $docente
                            )
                            : route('portal.docentes.index') }}"
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
        const employeeSelect = document.getElementById(
            'empleado_id'
        );

        const employeeCard = document.getElementById(
            'selectedEmployeeCard'
        );

        const employeeImage = document.getElementById(
            'selectedEmployeeImage'
        );

        const employeeInitials = document.getElementById(
            'selectedEmployeeInitials'
        );

        const employeeName = document.getElementById(
            'selectedEmployeeName'
        );

        const employeeCode = document.getElementById(
            'selectedEmployeeCode'
        );

        const employeeCodeWrapper = document.getElementById(
            'selectedEmployeeCodeWrapper'
        );

        const employeeDocument = document.getElementById(
            'selectedEmployeeDocument'
        );

        const employeeDocumentWrapper = document.getElementById(
            'selectedEmployeeDocumentWrapper'
        );

        const employeeEmail = document.getElementById(
            'selectedEmployeeEmail'
        );

        const employeeEmailWrapper = document.getElementById(
            'selectedEmployeeEmailWrapper'
        );

        const observations = document.getElementById(
            'observaciones'
        );

        const observationsCounter = document.getElementById(
            'observacionesCounter'
        );

        const updateEmployeePreview = () => {
            if (!employeeSelect || !employeeCard) {
                return;
            }

            const option =
                employeeSelect.options[
                    employeeSelect.selectedIndex
                ];

            if (!option || option.value === '') {
                employeeCard.hidden = true;
                return;
            }

            const data = option.dataset;

            employeeCard.hidden = false;

            if (employeeName) {
                employeeName.textContent =
                    data.name || 'Empleado seleccionado';
            }

            if (
                employeeCode &&
                employeeCodeWrapper
            ) {
                employeeCode.textContent =
                    data.employeeCode || '';

                employeeCodeWrapper.hidden =
                    !data.employeeCode;
            }

            if (
                employeeDocument &&
                employeeDocumentWrapper
            ) {
                employeeDocument.textContent =
                    data.document || '';

                employeeDocumentWrapper.hidden =
                    !data.document;
            }

            if (
                employeeEmail &&
                employeeEmailWrapper
            ) {
                employeeEmail.textContent =
                    data.email || '';

                employeeEmailWrapper.hidden =
                    !data.email;
            }

            if (data.photo) {
                employeeImage.src = data.photo;
                employeeImage.hidden = false;
                employeeInitials.hidden = true;
            } else {
                employeeImage.src = '';
                employeeImage.hidden = true;

                employeeInitials.textContent =
                    data.initials || 'DO';

                employeeInitials.hidden = false;
            }
        };

        const updateObservationsCounter = () => {
            if (
                !observations ||
                !observationsCounter
            ) {
                return;
            }

            observationsCounter.textContent =
                observations.value.length;
        };

        employeeSelect?.addEventListener(
            'change',
            updateEmployeePreview
        );

        observations?.addEventListener(
            'input',
            updateObservationsCounter
        );

        updateEmployeePreview();
        updateObservationsCounter();
    });
</script>

@endpush