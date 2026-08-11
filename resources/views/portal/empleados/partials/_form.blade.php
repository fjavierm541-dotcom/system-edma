@php
    $personaActual = $modoEdicion
        ? $empleado->persona
        : $personaSeleccionada;

    $personaIdActual = old(
        'persona_id',
        $modoEdicion
            ? $empleado->persona_id
            : $personaSeleccionada?->id
    );

    $fechaIngresoActual = old(
        'fecha_ingreso',
        $empleado?->fecha_ingreso?->format('Y-m-d')
            ?? now()->format('Y-m-d')
    );

    $fechaSalidaActual = old(
        'fecha_salida',
        $empleado?->fecha_salida?->format('Y-m-d')
    );

    $estadoActual = old(
        'estado',
        $empleado?->estado ?? 'activo'
    );
@endphp

<div class="row g-4">

    {{-- Columna principal --}}
    <div class="col-12 col-xl-8">

        {{-- Persona --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <div>
                    <h2>Persona asociada</h2>

                    <p>
                        Información personal vinculada al expediente laboral.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                @if ($modoEdicion)

                    <div class="portal-student-person-card">

                        <div class="portal-student-person-avatar">

                            @if ($personaActual?->foto_perfil)

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        $personaActual->foto_perfil
                                    ) }}"
                                    alt="Fotografía de {{ $personaActual->nombre_completo }}"
                                >

                            @else

                                <span>
                                    {{ $personaActual?->iniciales ?: 'EM' }}
                                </span>

                            @endif

                        </div>

                        <div class="portal-student-person-info">

                            <span class="portal-student-person-label">
                                Persona vinculada
                            </span>

                            <h3>
                                {{ $personaActual->nombre_completo }}
                            </h3>

                            <div class="portal-student-person-meta">

                                @if ($personaActual->numero_documento)

                                    <span>
                                        <i class="bi bi-credit-card-2-front"></i>
                                        {{ $personaActual->numero_documento }}
                                    </span>

                                @endif

                                @if ($personaActual->correo_personal)

                                    <span>
                                        <i class="bi bi-envelope"></i>
                                        {{ $personaActual->correo_personal }}
                                    </span>

                                @endif

                                @if ($personaActual->telefono_movil)

                                    <span>
                                        <i class="bi bi-phone"></i>
                                        {{ $personaActual->telefono_movil }}
                                    </span>

                                @endif

                            </div>

                        </div>

                        <a
                            href="{{ route(
                                'portal.personas.show',
                                $personaActual
                            ) }}"
                            class="btn portal-btn-secondary btn-sm"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <i class="bi bi-box-arrow-up-right"></i>
                            Ver persona
                        </a>

                    </div>

                    <div class="portal-form-help mt-3">
                        La persona vinculada y el código del empleado
                        no pueden modificarse después de crear el expediente.
                    </div>

                @else

                    <div class="mb-3">

                        <label
                            for="persona_id"
                            class="form-label portal-form-label"
                        >
                            Seleccione una persona
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="persona_id"
                            id="persona_id"
                            class="form-select portal-form-control
                                @error('persona_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Seleccione una persona disponible
                            </option>

                            @foreach ($personasDisponibles as $persona)

                                <option
                                    value="{{ $persona->id }}"
                                    data-name="{{ $persona->nombre_completo }}"
                                    data-document="{{ $persona->numero_documento }}"
                                    data-email="{{ $persona->correo_personal }}"
                                    data-phone="{{ $persona->telefono_movil }}"
                                    data-photo="{{ $persona->foto_perfil
                                        ? asset('storage/' . $persona->foto_perfil)
                                        : '' }}"
                                    data-initials="{{ $persona->iniciales }}"
                                    @selected(
                                        (string) $personaIdActual
                                        === (string) $persona->id
                                    )
                                >
                                    {{ $persona->nombre_completo }}

                                    @if ($persona->numero_documento)
                                        — {{ $persona->numero_documento }}
                                    @endif
                                </option>

                            @endforeach
                        </select>

                        @error('persona_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Solo aparecen personas activas que todavía
                            no poseen expediente de empleado.
                        </div>

                    </div>

                    @if ($personasDisponibles->isEmpty())

                        <div class="portal-inline-notice portal-inline-notice-warning">

                            <i class="bi bi-exclamation-triangle"></i>

                            <div>
                                <strong>No hay personas disponibles</strong>

                                <span>
                                    Registre primero una persona o revise
                                    si ya posee expediente laboral.
                                </span>
                            </div>

                            <a
                                href="{{ route('portal.personas.create') }}"
                                class="btn portal-btn-secondary btn-sm"
                            >
                                Nueva persona
                            </a>

                        </div>

                    @endif

                    <div
                        class="portal-student-person-card"
                        id="selectedPersonCard"
                        @if (!$personaSeleccionada) hidden @endif
                    >

                        <div class="portal-student-person-avatar">

                            <img
                                src="{{ $personaSeleccionada?->foto_perfil
                                    ? asset(
                                        'storage/' .
                                        $personaSeleccionada->foto_perfil
                                    )
                                    : '' }}"
                                alt="Fotografía de la persona"
                                id="selectedPersonImage"
                                @if (!$personaSeleccionada?->foto_perfil) hidden @endif
                            >

                            <span
                                id="selectedPersonInitials"
                                @if ($personaSeleccionada?->foto_perfil) hidden @endif
                            >
                                {{ $personaSeleccionada?->iniciales ?: 'EM' }}
                            </span>

                        </div>

                        <div class="portal-student-person-info">

                            <span class="portal-student-person-label">
                                Persona seleccionada
                            </span>

                            <h3 id="selectedPersonName">
                                {{ $personaSeleccionada?->nombre_completo }}
                            </h3>

                            <div class="portal-student-person-meta">

                                <span id="selectedPersonDocumentWrapper">
                                    <i class="bi bi-credit-card-2-front"></i>

                                    <span id="selectedPersonDocument">
                                        {{ $personaSeleccionada?->numero_documento }}
                                    </span>
                                </span>

                                <span id="selectedPersonEmailWrapper">
                                    <i class="bi bi-envelope"></i>

                                    <span id="selectedPersonEmail">
                                        {{ $personaSeleccionada?->correo_personal }}
                                    </span>
                                </span>

                                <span id="selectedPersonPhoneWrapper">
                                    <i class="bi bi-phone"></i>

                                    <span id="selectedPersonPhone">
                                        {{ $personaSeleccionada?->telefono_movil }}
                                    </span>
                                </span>

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </section>

        {{-- Información laboral --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-briefcase"></i>
                </div>

                <div>
                    <h2>Información laboral</h2>

                    <p>
                        Datos correspondientes a la relación laboral.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12 col-md-6">

                        <label
                            for="fecha_ingreso"
                            class="form-label portal-form-label"
                        >
                            Fecha de ingreso
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="date"
                            name="fecha_ingreso"
                            id="fecha_ingreso"
                            value="{{ $fechaIngresoActual }}"
                            max="{{ now()->format('Y-m-d') }}"
                            class="form-control portal-form-control
                                @error('fecha_ingreso') is-invalid @enderror"
                            required
                        >

                        @error('fecha_ingreso')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="fecha_salida"
                            class="form-label portal-form-label"
                        >
                            Fecha de salida
                        </label>

                        <input
                            type="date"
                            name="fecha_salida"
                            id="fecha_salida"
                            value="{{ $fechaSalidaActual }}"
                            class="form-control portal-form-control
                                @error('fecha_salida') is-invalid @enderror"
                        >

                        @error('fecha_salida')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Déjela vacía mientras la relación laboral continúe.
                        </div>

                    </div>

                    <div class="col-12 col-md-4">

                        <label
                            for="cantidad_hijos"
                            class="form-label portal-form-label"
                        >
                            Cantidad de hijos
                        </label>

                        <input
                            type="text"
                            name="cantidad_hijos"
                            id="cantidad_hijos"
                            value="{{ old(
                                'cantidad_hijos',
                                $empleado?->cantidad_hijos ?? 0
                            ) }}"
                            class="form-control portal-form-control
                                @error('cantidad_hijos') is-invalid @enderror"
                            maxlength="2"
                            inputmode="numeric"
                            pattern="[0-9]*"
                        >

                        @error('cantidad_hijos')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-8">

                        <label
                            for="institucion_laboral_actual"
                            class="form-label portal-form-label"
                        >
                            Institución laboral actual
                        </label>

                        <input
                            type="text"
                            name="institucion_laboral_actual"
                            id="institucion_laboral_actual"
                            value="{{ old(
                                'institucion_laboral_actual',
                                $empleado?->institucion_laboral_actual
                            ) }}"
                            class="form-control portal-form-control
                                @error('institucion_laboral_actual') is-invalid @enderror"
                            maxlength="180"
                        >

                        @error('institucion_laboral_actual')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12">

                        <label
                            for="horario_laboral_actual"
                            class="form-label portal-form-label"
                        >
                            Horario laboral actual
                        </label>

                        <input
                            type="text"
                            name="horario_laboral_actual"
                            id="horario_laboral_actual"
                            value="{{ old(
                                'horario_laboral_actual',
                                $empleado?->horario_laboral_actual
                            ) }}"
                            class="form-control portal-form-control
                                @error('horario_laboral_actual') is-invalid @enderror"
                            maxlength="150"
                            placeholder="Ej. Lunes a viernes, 8:00 a. m. - 5:00 p. m."
                        >

                        @error('horario_laboral_actual')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

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
                        Información administrativa adicional.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <textarea
                    name="observaciones"
                    id="observaciones"
                    rows="5"
                    maxlength="1000"
                    class="form-control portal-form-control
                        @error('observaciones') is-invalid @enderror"
                >{{ old(
                    'observaciones',
                    $empleado?->observaciones
                ) }}</textarea>

                <div class="portal-form-text-counter">

                    <span>
                        Campo opcional.
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

        </section>

    </div>

    {{-- Columna lateral --}}
    <div class="col-12 col-xl-4">

        <div class="portal-form-sidebar">

            {{-- Código --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-upc-scan"></i>
                    </div>

                    <div>
                        <h2>Código institucional</h2>

                        <p>
                            Identificador laboral del empleado.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    @if ($modoEdicion)

                        <div class="portal-student-code-panel">

                            <span>Código de empleado</span>

                            <strong>
                                {{ $empleado->codigo_empleado }}
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
                                El sistema asignará el correlativo al guardar.
                            </small>

                        </div>

                    @endif

                </div>

            </section>

            {{-- Estado --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-toggle-on"></i>
                    </div>

                    <div>
                        <h2>Estado laboral</h2>

                        <p>
                            Disponibilidad del expediente.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <label
                        for="estado"
                        class="form-label portal-form-label"
                    >
                        Estado del empleado
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
                        Un empleado inactivo conserva su historial laboral.
                    </div>

                </div>

            </section>

            {{-- Ayuda --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h2>Información importante</h2>
                        <p>Reglas del expediente.</p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <ul class="portal-form-guidelines">

                        <li>
                            <i class="bi bi-check-circle"></i>
                            <span>
                                Los datos personales se administran desde Personas.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>
                            <span>
                                Una persona solo puede tener un expediente laboral.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>
                            <span>
                                El código EDMA-EMP es generado automáticamente.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>
                            <span>
                                Ser empleado no significa automáticamente ser docente.
                            </span>
                        </li>

                    </ul>

                </div>

            </section>

            {{-- Acciones --}}
            <section class="portal-card portal-form-actions-card">

                <div class="portal-form-actions">

                    <button
                        type="submit"
                        class="btn portal-btn-primary w-100"
                        @disabled(
                            !$modoEdicion &&
                            $personasDisponibles->isEmpty()
                        )
                    >
                        <i class="bi bi-check2-circle"></i>

                        {{ $modoEdicion
                            ? 'Guardar cambios'
                            : 'Crear expediente' }}
                    </button>

                    <a
                        href="{{ $modoEdicion
                            ? route(
                                'portal.empleados.show',
                                $empleado
                            )
                            : route('portal.empleados.index') }}"
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
        const personSelect = document.getElementById(
            'persona_id'
        );

        const personCard = document.getElementById(
            'selectedPersonCard'
        );

        const personImage = document.getElementById(
            'selectedPersonImage'
        );

        const personInitials = document.getElementById(
            'selectedPersonInitials'
        );

        const personName = document.getElementById(
            'selectedPersonName'
        );

        const personDocument = document.getElementById(
            'selectedPersonDocument'
        );

        const personDocumentWrapper = document.getElementById(
            'selectedPersonDocumentWrapper'
        );

        const personEmail = document.getElementById(
            'selectedPersonEmail'
        );

        const personEmailWrapper = document.getElementById(
            'selectedPersonEmailWrapper'
        );

        const personPhone = document.getElementById(
            'selectedPersonPhone'
        );

        const personPhoneWrapper = document.getElementById(
            'selectedPersonPhoneWrapper'
        );

        const children = document.getElementById(
            'cantidad_hijos'
        );

        const admissionDate = document.getElementById(
            'fecha_ingreso'
        );

        const departureDate = document.getElementById(
            'fecha_salida'
        );

        const observations = document.getElementById(
            'observaciones'
        );

        const observationsCounter = document.getElementById(
            'observacionesCounter'
        );

        const allowOnlyDigits = (
            element,
            maximumLength
        ) => {
            if (!element) {
                return;
            }

            element.value = element.value
                .replace(/\D/g, '')
                .slice(0, maximumLength);
        };

        const updatePersonPreview = () => {
            if (!personSelect || !personCard) {
                return;
            }

            const option =
                personSelect.options[
                    personSelect.selectedIndex
                ];

            if (!option || option.value === '') {
                personCard.hidden = true;
                return;
            }

            const data = option.dataset;

            personCard.hidden = false;

            if (personName) {
                personName.textContent =
                    data.name || 'Persona seleccionada';
            }

            if (
                personDocument &&
                personDocumentWrapper
            ) {
                personDocument.textContent =
                    data.document || '';

                personDocumentWrapper.hidden =
                    !data.document;
            }

            if (
                personEmail &&
                personEmailWrapper
            ) {
                personEmail.textContent =
                    data.email || '';

                personEmailWrapper.hidden =
                    !data.email;
            }

            if (
                personPhone &&
                personPhoneWrapper
            ) {
                personPhone.textContent =
                    data.phone || '';

                personPhoneWrapper.hidden =
                    !data.phone;
            }

            if (data.photo) {
                personImage.src = data.photo;
                personImage.hidden = false;
                personInitials.hidden = true;
            } else {
                personImage.src = '';
                personImage.hidden = true;

                personInitials.textContent =
                    data.initials || 'EM';

                personInitials.hidden = false;
            }
        };

        const updateDepartureDate = () => {
            if (!admissionDate || !departureDate) {
                return;
            }

            departureDate.min =
                admissionDate.value || '';
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

        personSelect?.addEventListener(
            'change',
            updatePersonPreview
        );

        children?.addEventListener(
            'input',
            () => {
                allowOnlyDigits(
                    children,
                    2
                );
            }
        );

        admissionDate?.addEventListener(
            'change',
            updateDepartureDate
        );

        observations?.addEventListener(
            'input',
            updateObservationsCounter
        );

        updatePersonPreview();
        updateDepartureDate();
        updateObservationsCounter();

        if (children) {
            allowOnlyDigits(
                children,
                2
            );
        }
    });
</script>

@endpush