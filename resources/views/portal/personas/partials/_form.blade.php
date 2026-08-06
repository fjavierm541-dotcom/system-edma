@php
    $valor = function (
        string $campo,
        mixed $predeterminado = null
    ) use ($persona) {
        return old(
            $campo,
            $persona?->{$campo} ?? $predeterminado
        );
    };
@endphp

<div class="row g-4">

    {{-- =====================================================
         Columna principal
         ===================================================== --}}
    <div class="col-12 col-xl-9">

        {{-- Datos personales --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <div>
                    <h2>Datos personales</h2>

                    <p>
                        Información general de identificación de la persona.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12 col-md-6">

                        <label
                            for="primer_nombre"
                            class="form-label portal-form-label"
                        >
                            Primer nombre
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="primer_nombre"
                            id="primer_nombre"
                            value="{{ $valor('primer_nombre') }}"
                            class="form-control portal-form-control
                                @error('primer_nombre') is-invalid @enderror"
                            maxlength="50"
                            autocomplete="given-name"
                            required
                        >

                        @error('primer_nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="segundo_nombre"
                            class="form-label portal-form-label"
                        >
                            Segundo nombre
                        </label>

                        <input
                            type="text"
                            name="segundo_nombre"
                            id="segundo_nombre"
                            value="{{ $valor('segundo_nombre') }}"
                            class="form-control portal-form-control
                                @error('segundo_nombre') is-invalid @enderror"
                            maxlength="50"
                            autocomplete="additional-name"
                        >

                        @error('segundo_nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="primer_apellido"
                            class="form-label portal-form-label"
                        >
                            Primer apellido
                            <span class="portal-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="primer_apellido"
                            id="primer_apellido"
                            value="{{ $valor('primer_apellido') }}"
                            class="form-control portal-form-control
                                @error('primer_apellido') is-invalid @enderror"
                            maxlength="50"
                            autocomplete="family-name"
                            required
                        >

                        @error('primer_apellido')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="segundo_apellido"
                            class="form-label portal-form-label"
                        >
                            Segundo apellido
                        </label>

                        <input
                            type="text"
                            name="segundo_apellido"
                            id="segundo_apellido"
                            value="{{ $valor('segundo_apellido') }}"
                            class="form-control portal-form-control
                                @error('segundo_apellido') is-invalid @enderror"
                            maxlength="50"
                        >

                        @error('segundo_apellido')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-4">

                        <label
                            for="fecha_nacimiento"
                            class="form-label portal-form-label"
                        >
                            Fecha de nacimiento
                        </label>

                        <input
                            type="date"
                            name="fecha_nacimiento"
                            id="fecha_nacimiento"
                            value="{{ old(
                                'fecha_nacimiento',
                                $persona?->fecha_nacimiento?->format('Y-m-d')
                            ) }}"
                            max="{{ now()->format('Y-m-d') }}"
                            class="form-control portal-form-control
                                @error('fecha_nacimiento') is-invalid @enderror"
                        >

                        @error('fecha_nacimiento')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-4">

                        <label
                            for="sexo"
                            class="form-label portal-form-label"
                        >
                            Sexo
                        </label>

                        <select
                            name="sexo"
                            id="sexo"
                            class="form-select portal-form-control
                                @error('sexo') is-invalid @enderror"
                        >
                            <option value="">
                                Seleccione una opción
                            </option>

                            @foreach ($sexos as $clave => $etiqueta)
                                <option
                                    value="{{ $clave }}"
                                    @selected(
                                        $valor('sexo') === $clave
                                    )
                                >
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>

                        @error('sexo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-4">

                        <label
                            for="estado_civil"
                            class="form-label portal-form-label"
                        >
                            Estado civil
                        </label>

                        <select
                            name="estado_civil"
                            id="estado_civil"
                            class="form-select portal-form-control
                                @error('estado_civil') is-invalid @enderror"
                        >
                            <option value="">
                                Seleccione una opción
                            </option>

                            @foreach ($estadosCiviles as $clave => $etiqueta)
                                <option
                                    value="{{ $clave }}"
                                    @selected(
                                        $valor('estado_civil') === $clave
                                    )
                                >
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>

                        @error('estado_civil')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </section>

        {{-- Identificación --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>

                <div>
                    <h2>Identificación</h2>

                    <p>
                        Documento oficial, nacionalidad y RTN.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12 col-md-5">

                        <label
                            for="tipo_documento"
                            class="form-label portal-form-label"
                        >
                            Tipo de documento
                        </label>

                        <select
                            name="tipo_documento"
                            id="tipo_documento"
                            class="form-select portal-form-control
                                @error('tipo_documento') is-invalid @enderror"
                        >
                            <option value="">
                                Sin documento
                            </option>

                            @foreach ($tiposDocumento as $clave => $etiqueta)
                                <option
                                    value="{{ $clave }}"
                                    @selected(
                                        $valor('tipo_documento') === $clave
                                    )
                                >
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>

                        @error('tipo_documento')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-7">

                        <label
                            for="numero_documento"
                            class="form-label portal-form-label"
                        >
                            Número de documento
                        </label>

                        <input
                            type="text"
                            name="numero_documento"
                            id="numero_documento"
                            value="{{ $valor('numero_documento') }}"
                            class="form-control portal-form-control
                                @error('numero_documento') is-invalid @enderror"
                            maxlength="50"
                            autocomplete="off"
                        >

                        <div
                            class="form-text portal-form-help"
                            id="documentoHelp"
                        >
                            Seleccione primero el tipo de documento.
                        </div>

                        @error('numero_documento')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="nacionalidad"
                            class="form-label portal-form-label"
                        >
                            Nacionalidad
                        </label>

                        <input
                            type="text"
                            name="nacionalidad"
                            id="nacionalidad"
                            value="{{ $valor('nacionalidad') }}"
                            class="form-control portal-form-control
                                @error('nacionalidad') is-invalid @enderror"
                            maxlength="100"
                        >

                        @error('nacionalidad')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="rtn"
                            class="form-label portal-form-label"
                        >
                            RTN
                        </label>

                        <input
                            type="text"
                            name="rtn"
                            id="rtn"
                            value="{{ $valor('rtn') }}"
                            class="form-control portal-form-control
                                @error('rtn') is-invalid @enderror"
                            maxlength="30"
                            autocomplete="off"
                        >

                        <div class="form-text portal-form-help">
                            Campo opcional.
                        </div>

                        @error('rtn')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </section>

        {{-- Información de contacto --}}
        <section class="portal-card portal-form-card">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-telephone"></i>
                </div>

                <div>
                    <h2>Información de contacto</h2>

                    <p>
                        Correo electrónico y números telefónicos.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12">

                        <label
                            for="correo_personal"
                            class="form-label portal-form-label"
                        >
                            Correo personal
                        </label>

                        <input
                            type="email"
                            name="correo_personal"
                            id="correo_personal"
                            value="{{ $valor('correo_personal') }}"
                            class="form-control portal-form-control
                                @error('correo_personal') is-invalid @enderror"
                            maxlength="150"
                            autocomplete="email"
                        >

                        @error('correo_personal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="telefono_movil"
                            class="form-label portal-form-label"
                        >
                            Teléfono móvil
                        </label>

                        <input
                            type="tel"
                            name="telefono_movil"
                            id="telefono_movil"
                            value="{{ $valor('telefono_movil') }}"
                            class="form-control portal-form-control
                                @error('telefono_movil') is-invalid @enderror"
                            maxlength="30"
                            autocomplete="tel"
                        >

                        @error('telefono_movil')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="telefono_fijo"
                            class="form-label portal-form-label"
                        >
                            Teléfono fijo
                        </label>

                        <input
                            type="tel"
                            name="telefono_fijo"
                            id="telefono_fijo"
                            value="{{ $valor('telefono_fijo') }}"
                            class="form-control portal-form-control
                                @error('telefono_fijo') is-invalid @enderror"
                            maxlength="30"
                        >

                        @error('telefono_fijo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12">

                        <div class="portal-form-check-card">

                            <div>
                                <i class="bi bi-whatsapp"></i>

                                <span>
                                    El número móvil también está disponible
                                    en WhatsApp
                                </span>
                            </div>

                            <div class="form-check form-switch m-0">

                                {{-- Garantiza que se envíe 0 si está desmarcado --}}
                                <input
                                    type="hidden"
                                    name="telefono_movil_whatsapp"
                                    value="0"
                                >

                                <input
                                    type="checkbox"
                                    name="telefono_movil_whatsapp"
                                    id="telefono_movil_whatsapp"
                                    value="1"
                                    class="form-check-input"
                                    @checked(
                                        old(
                                            'telefono_movil_whatsapp',
                                            $persona?->telefono_movil_whatsapp
                                                ?? false
                                        )
                                    )
                                    @disabled(
                                        blank(
                                            old(
                                                'telefono_movil',
                                                $persona?->telefono_movil
                                            )
                                        )
                                    )
                                >

                            </div>

                        </div>

                        <div class="portal-form-help">
                            Esta opción se habilitará cuando ingrese un
                            teléfono móvil.
                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- Residencia --}}
        <section class="portal-card portal-form-card mb-0">

            <div class="portal-form-section-header">

                <div class="portal-form-section-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>

                <div>
                    <h2>Residencia</h2>

                    <p>
                        Ubicación y dirección actual de la persona.
                    </p>
                </div>

            </div>

            <div class="portal-form-section-body">

                <div class="row g-3">

                    <div class="col-12 col-md-6">

                        <label
                            for="pais_residencia_id"
                            class="form-label portal-form-label"
                        >
                            País de residencia
                        </label>

                        <select
                            name="pais_residencia_id"
                            id="pais_residencia_id"
                            class="form-select portal-form-control
                                @error('pais_residencia_id') is-invalid @enderror"
                        >
                            <option value="">
                                Seleccione un país
                            </option>

                            @foreach ($paises as $pais)
                                <option
                                    value="{{ $pais->id }}"
                                    data-nationality="{{ $pais->nacionalidad }}"
                                    @selected(
                                        (string) $valor(
                                            'pais_residencia_id',
                                            $paisPredeterminado ?? null
                                        ) === (string) $pais->id
                                    )
                                >
                                    {{ $pais->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('pais_residencia_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="departamento_estado"
                            class="form-label portal-form-label"
                        >
                            Departamento o estado
                        </label>

                        <input
                            type="text"
                            name="departamento_estado"
                            id="departamento_estado"
                            value="{{ $valor('departamento_estado') }}"
                            class="form-control portal-form-control
                                @error('departamento_estado') is-invalid @enderror"
                            maxlength="120"
                        >

                        @error('departamento_estado')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12 col-md-6">

                        <label
                            for="ciudad_municipio"
                            class="form-label portal-form-label"
                        >
                            Ciudad o municipio
                        </label>

                        <input
                            type="text"
                            name="ciudad_municipio"
                            id="ciudad_municipio"
                            value="{{ $valor('ciudad_municipio') }}"
                            class="form-control portal-form-control
                                @error('ciudad_municipio') is-invalid @enderror"
                            maxlength="120"
                        >

                        @error('ciudad_municipio')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-12">

                        <label
                            for="direccion"
                            class="form-label portal-form-label"
                        >
                            Dirección
                        </label>

                        <textarea
                            name="direccion"
                            id="direccion"
                            rows="3"
                            class="form-control portal-form-control
                                @error('direccion') is-invalid @enderror"
                        >{{ $valor('direccion') }}</textarea>

                        @error('direccion')
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
    <div class="col-12 col-xl-3">

        <div class="portal-form-sidebar">

            {{-- Fotografía --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-camera"></i>
                    </div>

                    <div>
                        <h2>Fotografía</h2>

                        <p>
                            Imagen opcional del perfil.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <div class="portal-photo-uploader">

                        <div class="portal-photo-preview">

                            @if ($persona?->foto_perfil)

                                <img
                                    src="{{ asset(
                                        'storage/' . $persona->foto_perfil
                                    ) }}"
                                    alt="Fotografía actual"
                                    id="photoPreviewImage"
                                    data-original-src="{{ asset(
                                        'storage/' . $persona->foto_perfil
                                    ) }}"
                                >

                            @else

                                <img
                                    src=""
                                    alt="Vista previa"
                                    id="photoPreviewImage"
                                    data-original-src=""
                                    hidden
                                >

                            @endif

                            <div
                                class="portal-photo-placeholder"
                                id="photoPlaceholder"
                                @if ($persona?->foto_perfil) hidden @endif
                            >
                                <i class="bi bi-person"></i>

                                <span>
                                    Sin fotografía
                                </span>
                            </div>

                        </div>

                        <label
                            for="foto_perfil"
                            class="btn portal-btn-secondary w-100"
                        >
                            <i class="bi bi-upload"></i>
                            Seleccionar imagen
                        </label>

                        <input
                            type="file"
                            name="foto_perfil"
                            id="foto_perfil"
                            class="d-none
                                @error('foto_perfil') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <small class="portal-form-help text-center">
                            JPG, PNG o WEBP. Máximo 3 MB.
                        </small>

                        @error('foto_perfil')
                            <div class="text-danger small text-center">
                                {{ $message }}
                            </div>
                        @enderror

                        @if (
                            $modoEdicion &&
                            $persona?->foto_perfil
                        )

                            <div class="form-check mt-2">

                                <input
                                    type="checkbox"
                                    name="eliminar_foto_perfil"
                                    id="eliminar_foto_perfil"
                                    value="1"
                                    class="form-check-input"
                                    @checked(
                                        old('eliminar_foto_perfil')
                                    )
                                >

                                <label
                                    for="eliminar_foto_perfil"
                                    class="form-check-label"
                                >
                                    Eliminar fotografía actual
                                </label>

                            </div>

                        @endif

                    </div>

                </div>

            </section>

            {{-- Estado --}}
            <section class="portal-card portal-form-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-toggle-on"></i>
                    </div>

                    <div>
                        <h2>Estado</h2>

                        <p>
                            Disponibilidad del registro.
                        </p>
                    </div>

                </div>

                <div class="portal-form-section-body">

                    <label
                        for="estado"
                        class="form-label portal-form-label"
                    >
                        Estado de la persona
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        class="form-select portal-form-control
                            @error('estado') is-invalid @enderror"
                    >
                        <option
                            value="activo"
                            @selected(
                                $valor('estado', 'activo') === 'activo'
                            )
                        >
                            Activa
                        </option>

                        <option
                            value="inactivo"
                            @selected(
                                $valor('estado', 'activo') === 'inactivo'
                            )
                        >
                            Inactiva
                        </option>
                    </select>

                    <div class="portal-form-help mt-2">
                        Las personas inactivas conservan toda su información
                        histórica.
                    </div>

                    @error('estado')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

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
                            : 'Registrar persona' }}
                    </button>

                    <a
                        href="{{ $modoEdicion
                            ? route(
                                'portal.personas.show',
                                $persona
                            )
                            : route('portal.personas.index') }}"
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
            const documentType = document.getElementById(
                'tipo_documento'
            );

            const documentNumber = document.getElementById(
                'numero_documento'
            );

            const documentHelp = document.getElementById(
                'documentoHelp'
            );

            const country = document.getElementById(
                'pais_residencia_id'
            );

            const nationality = document.getElementById(
                'nacionalidad'
            );

            const mobilePhone = document.getElementById(
                'telefono_movil'
            );

            const whatsappSwitch = document.getElementById(
                'telefono_movil_whatsapp'
            );

            const photoInput = document.getElementById(
                'foto_perfil'
            );

            const photoImage = document.getElementById(
                'photoPreviewImage'
            );

            const photoPlaceholder = document.getElementById(
                'photoPlaceholder'
            );

            const removePhoto = document.getElementById(
                'eliminar_foto_perfil'
            );

            /*
            |--------------------------------------------------------------------------
            | Documento de identificación
            |--------------------------------------------------------------------------
            */

            const updateDocumentField = () => {
                if (
                    !documentType ||
                    !documentNumber ||
                    !documentHelp
                ) {
                    return;
                }

                const type = documentType.value;

                const settings = {
                    dni: {
                        placeholder: 'Ejemplo: 0801-1998-12345',
                        help: 'Ingrese el Documento Nacional de Identificación.',
                    },

                    identidad_menor: {
                        placeholder: 'Ingrese el número de identidad',
                        help: 'Utilice el número de identidad asignado al menor.',
                    },

                    pasaporte: {
                        placeholder: 'Ingrese el número de pasaporte',
                        help: 'El pasaporte puede contener letras y números.',
                    },

                    otro: {
                        placeholder: 'Ingrese el número del documento',
                        help: 'Ingrese el número del documento presentado.',
                    },
                };

                const selected = settings[type];

                documentNumber.disabled = type === '';
                documentNumber.required = type !== '';

                documentNumber.placeholder = selected
                    ? selected.placeholder
                    : 'Seleccione primero el tipo de documento';

                documentHelp.textContent = selected
                    ? selected.help
                    : 'Seleccione primero el tipo de documento';

                if (type === '') {
                    documentNumber.value = '';
                }
            };

            /*
            |--------------------------------------------------------------------------
            | Nacionalidad sugerida
            |--------------------------------------------------------------------------
            */

            const updateNationality = () => {
                if (!country || !nationality) {
                    return;
                }

                const selectedOption =
                    country.options[country.selectedIndex];

                const selectedNationality =
                    selectedOption?.dataset.nationality ?? '';

                /*
                 * Solo se completa automáticamente cuando el campo
                 * está vacío. Nunca reemplaza un valor escrito por
                 * el usuario.
                 */
                if (
                    nationality.value.trim() === '' &&
                    selectedNationality !== ''
                ) {
                    nationality.value = selectedNationality;
                }
            };

            /*
            |--------------------------------------------------------------------------
            | Disponibilidad de WhatsApp
            |--------------------------------------------------------------------------
            */

            const updateWhatsappAvailability = () => {
                if (!mobilePhone || !whatsappSwitch) {
                    return;
                }

                const hasMobilePhone =
                    mobilePhone.value.trim() !== '';

                whatsappSwitch.disabled = !hasMobilePhone;

                if (!hasMobilePhone) {
                    whatsappSwitch.checked = false;
                }
            };

            /*
            |--------------------------------------------------------------------------
            | Vista previa de fotografía
            |--------------------------------------------------------------------------
            */

            const showPhotoPlaceholder = () => {
                if (!photoImage || !photoPlaceholder) {
                    return;
                }

                photoImage.hidden = true;
                photoPlaceholder.hidden = false;
            };

            const showPhotoImage = source => {
                if (
                    !photoImage ||
                    !photoPlaceholder ||
                    !source
                ) {
                    return;
                }

                photoImage.src = source;
                photoImage.hidden = false;
                photoPlaceholder.hidden = true;
            };

            const previewPhoto = event => {
                const file = event.target.files?.[0];

                if (!file) {
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    event.target.value = '';

                    alert(
                        'El archivo seleccionado debe ser una imagen.'
                    );

                    return;
                }

                const maximumSize = 3 * 1024 * 1024;

                if (file.size > maximumSize) {
                    event.target.value = '';

                    alert(
                        'La fotografía no puede superar los 3 MB.'
                    );

                    return;
                }

                const reader = new FileReader();

                reader.addEventListener('load', () => {
                    showPhotoImage(reader.result);

                    if (removePhoto) {
                        removePhoto.checked = false;
                    }
                });

                reader.readAsDataURL(file);
            };

            const updatePhotoRemoval = () => {
                if (!removePhoto || !photoImage) {
                    return;
                }

                if (removePhoto.checked) {
                    if (photoInput) {
                        photoInput.value = '';
                    }

                    showPhotoPlaceholder();

                    return;
                }

                const originalSource =
                    photoImage.dataset.originalSrc ?? '';

                if (originalSource !== '') {
                    showPhotoImage(originalSource);
                }
            };

            /*
            |--------------------------------------------------------------------------
            | Eventos
            |--------------------------------------------------------------------------
            */

            documentType?.addEventListener(
                'change',
                updateDocumentField
            );

            country?.addEventListener(
                'change',
                updateNationality
            );

            mobilePhone?.addEventListener(
                'input',
                updateWhatsappAvailability
            );

            photoInput?.addEventListener(
                'change',
                previewPhoto
            );

            removePhoto?.addEventListener(
                'change',
                updatePhotoRemoval
            );

            /*
            |--------------------------------------------------------------------------
            | Estado inicial
            |--------------------------------------------------------------------------
            */

            updateDocumentField();
            updateWhatsappAvailability();

            if (removePhoto?.checked) {
                updatePhotoRemoval();
            }
        });
    </script>

@endpush