@if (session('success'))
    <div
        class="alert alert-success portal-alert alert-dismissible fade show"
        role="alert"
    >
        <i class="bi bi-check-circle-fill"></i>

        <div>
            <strong>Proceso completado</strong>
            <span>{{ session('success') }}</span>
        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Cerrar"
        ></button>
    </div>
@endif

@if (session('error'))
    <div
        class="alert alert-danger portal-alert alert-dismissible fade show"
        role="alert"
    >
        <i class="bi bi-exclamation-octagon-fill"></i>

        <div>
            <strong>No fue posible completar el proceso</strong>
            <span>{{ session('error') }}</span>
        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Cerrar"
        ></button>
    </div>
@endif

@if ($errors->any())
    <div
        class="alert alert-danger portal-alert alert-dismissible fade show"
        role="alert"
    >
        <i class="bi bi-exclamation-triangle-fill"></i>

        <div>
            <strong>Revise la información ingresada</strong>

            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Cerrar"
        ></button>
    </div>
@endif