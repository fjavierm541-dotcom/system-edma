@php
    $modoEdicion = $modoEdicion ?? false;

    $programaIdActual = old(
        'programa_id',
        $nivel->programa_id
            ?? $programaSeleccionado?->id
            ?? ''
    );

    $ordenActual = old(
        'orden',
        $nivel->orden ?? ''
    );

    $duracionActual = old(
        'duracion_semanas',
        $nivel->duracion_semanas ?? 12
    );

    $notaMinimaActual = old(
        'nota_minima_aprobacion',
        $nivel->nota_minima_aprobacion ?? '70.00'
    );

    $estadoActual = old(
        'estado',
        $nivel->estado ?? 'activo'
    );
@endphp

<div class="row g-4">

    {{-- =====================================================
         Información del nivel
         ===================================================== --}}
    <div class="col-12 col-xl-8">

        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-layers"></i>
                </div>

                <div>
                    <h2>Información del nivel</h2>

                    <p>
                        Configure la identificación y posición
                        académica del nivel dentro del programa.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    {{-- Programa --}}
                    <div class="col-12">

                        <label
                            for="programa_id"
                            class="form-label portal-form-label"
                        >
                            Programa académico
                            <span class="portal-required">*</span>
                        </label>

                        <select
                            name="programa_id"
                            id="programa_id"
                            class="form-select portal-form-control
                                @error('programa_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Seleccione un programa
                            </option>

                            @foreach ($programas as $programa)

                                <option
                                    value="{{ $programa->id }}"
                                    data-segment="{{ $programa->segmento }}"
                                    @selected(
                                        (string) $programaIdActual
                                        === (string) $programa->id
                                    )
                                >
                                    {{ $programa->nombre }}
                                    — {{ $programa->codigo }}
                                </option>

                            @endforeach

                        </select>

                        @error('programa_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            El nivel pertenecerá exclusivamente al
                            programa seleccionado.
                        </div>

                    </div>

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
                                $nivel->codigo ?? ''
                            ) }}"
                            class="form-control portal-form-control
                                @error('codigo') is-invalid @enderror"
                            maxlength="20"
                            autocomplete="off"
                            placeholder="Ej. A0"
                            required
                        >

                        @error('codigo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Ejemplo: A0, A1, A2.
                        </div>

                    </div>

                    {{-- Nombre --}}
                    <div class="col-12 col-md-5">

                        <label
                            for="nombre"
                            class="form-label portal-form-label"
                        >
                            Nombre
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old(
                                'nombre',
                                $nivel->nombre ?? ''
                            ) }}"
                            class="form-control portal-form-control
                                @error('nombre') is-invalid @enderror"
                            maxlength="100"
                            placeholder="Ej. A0"
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Puede coincidir con el código si esa es
                            la nomenclatura oficial.
                        </div>

                    </div>

                    {{-- Orden --}}
                    <div class="col-12 col-md-3">

                        <label
                            for="orden"
                            class="form-label portal-form-label"
                        >
                            Orden
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="orden"
                            id="orden"
                            value="{{ $ordenActual }}"
                            class="form-control portal-form-control
                                @error('orden') is-invalid @enderror"
                            maxlength="3"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            placeholder="1"
                            required
                        >

                        @error('orden')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="portal-form-help">
                            Posición dentro del programa.
                        </div>

                    </div>

                    {{-- Duración --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="duracion_semanas"
                            class="form-label portal-form-label"
                        >
                            Duración
                            <span class="portal-required">*</span>
                        </label>

                        <div class="input-group">

                            <input
                                type="text"
                                name="duracion_semanas"
                                id="duracion_semanas"
                                value="{{ $duracionActual }}"
                                class="form-control portal-form-control
                                    @error('duracion_semanas') is-invalid @enderror"
                                maxlength="3"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                required
                            >

                            <span class="input-group-text">
                                semanas
                            </span>

                            @error('duracion_semanas')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="portal-form-help">
                            La duración actual de los niveles de EDMA
                            es de 12 semanas.
                        </div>

                    </div>

                    {{-- Nota mínima --}}
                    <div class="col-12 col-md-6">

                        <label
                            for="nota_minima_aprobacion"
                            class="form-label portal-form-label"
                        >
                            Nota mínima de aprobación
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="nota_minima_aprobacion"
                                id="nota_minima_aprobacion"
                                value="{{ $notaMinimaActual }}"
                                class="form-control portal-form-control
                                    @error('nota_minima_aprobacion') is-invalid @enderror"
                                min="0"
                                max="100"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                / 100
                            </span>

                            @error('nota_minima_aprobacion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="portal-form-help">
                            La nota mínima oficial es 70 sobre 100.
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
                            placeholder="Descripción general del nivel..."
                        >{{ old(
                            'descripcion',
                            $nivel->descripcion ?? ''
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
         Columna lateral
         ===================================================== --}}
    <div class="col-12 col-xl-4">

        <div class="portal-form-sidebar">

            {{-- Configuración --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-sliders"></i>
                    </div>

                    <div>
                        <h2>Configuración</h2>

                        <p>
                            Estado académico del nivel.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

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
                        Los niveles inactivos conservarán todo
                        su historial académico.
                    </div>

                </div>

            </section>

            {{-- Referencia EDMA --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <div>
                        <h2>Referencia académica</h2>
                        <p>Configuración actual de EDMA.</p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <ul class="portal-form-guidelines mb-0">

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Los programas manejan actualmente
                                7 niveles académicos.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Cada nivel tiene una duración
                                estándar de 12 semanas.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                La nota mínima de aprobación
                                es 70 sobre 100.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Los códigos académicos pueden utilizar
                                nomenclatura como A0, A1 y A2.
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
                            : 'Registrar nivel' }}
                    </button>

                    <a
                        href="{{ $modoEdicion
                            ? route(
                                'portal.niveles.show',
                                $nivel
                            )
                            : route('portal.niveles.index') }}"
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
        const code = document.getElementById(
            'codigo'
        );

        const order = document.getElementById(
            'orden'
        );

        const duration = document.getElementById(
            'duracion_semanas'
        );

        const description = document.getElementById(
            'descripcion'
        );

        const descriptionCounter = document.getElementById(
            'descripcionCounter'
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

        code?.addEventListener('input', () => {
            code.value = code.value
                .toUpperCase()
                .replace(/[^A-Z0-9_-]/g, '')
                .slice(0, 20);
        });

        order?.addEventListener(
            'input',
            () => allowOnlyDigits(order, 3)
        );

        duration?.addEventListener(
            'input',
            () => allowOnlyDigits(duration, 3)
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

        description?.addEventListener(
            'input',
            updateDescriptionCounter
        );

        updateDescriptionCounter();
    });
</script>

@endpush