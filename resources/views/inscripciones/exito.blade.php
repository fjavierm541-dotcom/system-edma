@extends('layouts.web')

@section('content')

    <div class="container py-5">

        <h1>
            Solicitud recibida
        </h1>

        <p>
            Su solicitud fue enviada correctamente.
        </p>

        <p>
            Código de solicitud:
            <strong>
                {{ $codigoSolicitud }}
            </strong>
        </p>

    </div>

@endsection