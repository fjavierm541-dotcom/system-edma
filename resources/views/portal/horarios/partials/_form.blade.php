@php
    $modoEdicion = $modoEdicion ?? false;

    $horaInicio = old(
        'hora_inicio',
        isset($horario) && $horario?->hora_inicio
            ? substr($horario->hora_inicio, 0, 5)
            : ''
    );

    $horaFin = old(
        'hora_fin',
        isset($horario) && $horario?->hora_fin
            ? substr($horario->hora_fin, 0, 5)
            : ''
    );

    $activoActual = old(
        'activo',
        isset($horario)
            ? ($horario->activo ? '1' : '0')
            : '1'
    );
@endphp

<div class="row g-4">

    {{-- =====================================================
         Información del horario
         ===================================================== --}}
    <div class="col-12 col-xl-8">

        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-clock"></i>
                </div>

                <div>
                    <h2>Información del horario</h2>

                    <p>
                        Defina una franja horaria que podrá utilizarse
                        posteriormente en uno o varios grupos.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-12">

                        <label
                            for="nombre"
                            class="form-label portal-form-label"
                        >
                            Nombre del horario
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old(
                                'nombre',
                                $horario->nombre ?? ''
                            ) }}"
                            class="form-control portal-form-control
                                @error('nombre') is-invalid @enderror"
                            maxlength="100"
                            placeholder="Ej. Jornada vespertina 2:00 p. m. - 3:00 p. m."
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Utilice un nombre que permita reconocer
                            fácilmente esta franja horaria.
                        </div>

                    </div>

                    {{-- Inicio --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="hora_inicio"
                            class="form-label portal-form-label"
                        >
                            Hora de inicio
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="time"
                            name="hora_inicio"
                            id="hora_inicio"
                            value="{{ $horaInicio }}"
                            class="form-control portal-form-control
                                @error('hora_inicio') is-invalid @enderror"
                            required
                        >

                        @error('hora_inicio')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Fin --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="hora_fin"
                            class="form-label portal-form-label"
                        >
                            Hora de finalización
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="time"
                            name="hora_fin"
                            id="hora_fin"
                            value="{{ $horaFin }}"
                            class="form-control portal-form-control
                                @error('hora_fin') is-invalid @enderror"
                            required
                        >

                        @error('hora_fin')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Zona horaria --}}
                    <div class="col-12">

                        <label
                            for="zona_horaria"
                            class="form-label portal-form-label"
                        >
                            Zona horaria
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="zona_horaria"
                            id="zona_horaria"
                            class="form-select portal-form-control
                                @error('zona_horaria') is-invalid @enderror"
                            required
                        >
                            <option
                                value="America/Tegucigalpa"
                                @selected(
                                    old(
                                        'zona_horaria',
                                        $horario->zona_horaria
                                            ?? 'America/Tegucigalpa'
                                    ) === 'America/Tegucigalpa'
                                )
                            >
                                Honduras (UTC-6)
                            </option>
                        </select>

                        @error('zona_horaria')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Los horarios de la academia se mostrarán
                            utilizando la hora de Honduras.
                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- Vista previa --}}
        <section class="portal-card portal-form-card mb-0">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-eye"></i>
                </div>

                <div>
                    <h2>Vista previa</h2>

                    <p>
                        Compruebe cómo se mostrará esta franja
                        horaria en otras secciones del sistema.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="portal-detail-item">

                    <span id="schedulePreviewName">
                        Horario
                    </span>

                    <strong id="schedulePreviewTime">
                        Seleccione la hora de inicio y finalización.
                    </strong>

                    <small>
                        Hora de Honduras
                    </small>

                </div>

            </div>

        </section>

    </div>

    {{-- =====================================================
         Columna lateral
         ===================================================== --}}
    <div class="col-12 col-xl-4">

        <div class="portal-form-sidebar">

            {{-- Estado --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-toggle-on"></i>
                    </div>

                    <div>
                        <h2>Disponibilidad</h2>

                        <p>
                            Indique si este horario puede utilizarse.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <label
                        for="activo"
                        class="form-label portal-form-label"
                    >
                        Estado del horario
                    </label>

                    <select
                        name="activo"
                        id="activo"
                        class="form-select portal-form-control
                            @error('activo') is-invalid @enderror"
                        required
                    >
                        <option
                            value="1"
                            @selected(
                                (string) $activoActual === '1'
                            )
                        >
                            Activo
                        </option>

                        <option
                            value="0"
                            @selected(
                                (string) $activoActual === '0'
                            )
                        >
                            Inactivo
                        </option>

                    </select>

                    @error('activo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="portal-form-help mt-2">
                        Un horario inactivo conservará sus registros,
                        pero no deberá utilizarse en nuevas asignaciones.
                    </div>

                </div>

            </section>

            {{-- Ayuda --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>

                    <div>
                        <h2>Uso de los horarios</h2>

                        <p>
                            Considere estas recomendaciones.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <ul class="portal-form-guidelines mb-0">

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Cree una franja una sola vez y podrá
                                utilizarla en diferentes grupos.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                El día de clase se seleccionará al
                                asignar este horario a un grupo.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                El nombre debe ayudar al personal
                                a reconocer rápidamente el horario.
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
                    >
                        <i class="bi bi-check2-circle"></i>

                        {{ $modoEdicion
                            ? 'Guardar cambios'
                            : 'Registrar horario' }}
                    </button>

                    <a
                        href="{{ $modoEdicion
                            ? route(
                                'portal.horarios.show',
                                $horario
                            )
                            : route('portal.horarios.index') }}"
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
        const nameInput = document.getElementById(
            'nombre'
        );

        const startInput = document.getElementById(
            'hora_inicio'
        );

        const endInput = document.getElementById(
            'hora_fin'
        );

        const previewName = document.getElementById(
            'schedulePreviewName'
        );

        const previewTime = document.getElementById(
            'schedulePreviewTime'
        );

        const formatTime = value => {
            if (!value) {
                return null;
            }

            const [hours, minutes] = value
                .split(':')
                .map(Number);

            const date = new Date();

            date.setHours(
                hours,
                minutes,
                0,
                0
            );

            return new Intl.DateTimeFormat(
                'es-HN',
                {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                }
            ).format(date);
        };

        const updatePreview = () => {
            if (previewName) {
                previewName.textContent =
                    nameInput?.value.trim() ||
                    'Horario';
            }

            if (!previewTime) {
                return;
            }

            const start = formatTime(
                startInput?.value
            );

            const end = formatTime(
                endInput?.value
            );

            if (start && end) {
                previewTime.textContent =
                    `${start} - ${end}`;
            } else {
                previewTime.textContent =
                    'Seleccione la hora de inicio y finalización.';
            }
        };

        nameInput?.addEventListener(
            'input',
            updatePreview
        );

        startInput?.addEventListener(
            'change',
            updatePreview
        );

        endInput?.addEventListener(
            'change',
            updatePreview
        );

        updatePreview();
    });
</script>

@endpush