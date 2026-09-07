@extends('layouts.web')

@section('content')

<section class="edma-solicitud-exito">
    <div class="container">

        <div class="edma-solicitud-exito__card">

            <div class="edma-solicitud-exito__icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <h1 class="edma-solicitud-exito__title">
                Solicitud recibida correctamente
            </h1>

            <p class="edma-solicitud-exito__intro">
                Hemos recibido su solicitud de inscripción y la información
                proporcionada será revisada por el personal de Edumerican Academy.
            </p>

            <div class="edma-solicitud-exito__codigo">
                <span>Código de solicitud</span>

                <strong>
                    {{ $codigoSolicitud }}
                </strong>
            </div>

            <div class="edma-solicitud-exito__alert">
                <i class="bi bi-info-circle"></i>

                <div>
                    <strong>Conserve este código.</strong>

                    <p class="mb-0">
                        Puede utilizarlo como referencia al comunicarse con la academia
                        para consultar el estado de su solicitud.
                    </p>
                </div>
            </div>

            <div class="edma-solicitud-exito__section">

                <h2>
                    ¿Qué sucede ahora?
                </h2>

                <div class="edma-solicitud-exito__steps">

                    <div class="edma-solicitud-exito__step">
                        <span>1</span>

                        <div>
                            <strong>Revisión de la información</strong>
                            <p>
                                El personal de EDMA verificará los datos registrados
                                y el comprobante de pago enviado.
                            </p>
                        </div>
                    </div>

                    <div class="edma-solicitud-exito__step">
                        <span>2</span>

                        <div>
                            <strong>Contacto si es necesario</strong>
                            <p>
                                Si se requiere información adicional o alguna corrección,
                                la academia se comunicará con usted.
                            </p>
                        </div>
                    </div>

                    <div class="edma-solicitud-exito__step">
                        <span>3</span>

                        <div>
                            <strong>Resultado de la solicitud</strong>
                            <p>
                                Una vez finalizada la revisión, se le informará el
                                resultado correspondiente y los pasos a seguir.
                            </p>
                        </div>
                    </div>

                </div>

            </div>

            <div class="edma-solicitud-exito__notice">
                <strong>
                    Importante
                </strong>

                <p class="mb-0">
                    El envío de esta solicitud no representa todavía una matrícula
                    confirmada. La información y el pago deben ser revisados y
                    aprobados por Edumerican Academy.
                </p>
            </div>

            <div class="edma-solicitud-exito__contacto">

                <h2>
                    ¿Necesita dar seguimiento?
                </h2>

                <p>
                    Puede comunicarse con Edumerican Academy por WhatsApp o correo electrónico
                    e indicar su código de solicitud para recibir asistencia.
                </p>

               <div class="edma-solicitud-exito__contact-buttons">

                    <a
                        href="https://wa.me/50496734171"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn edma-solicitud-exito__btn-primary"
                    >
                        <i class="bi bi-whatsapp"></i>
                        WhatsApp
                    </a>

                    <a
                        href="mailto:edumerican@gmail.com"
                        class="btn edma-solicitud-exito__btn-secondary"
                    >
                        <i class="bi bi-envelope"></i>
                        Correo electrónico
                    </a>

                    <a
                        href="{{ url('/') }}"
                        class="btn edma-solicitud-exito__btn-secondary"
                    >
                        <i class="bi bi-house"></i>
                        Volver al inicio
                    </a>

                </div>

            </div>

        </div>

    </div>
</section>

<style>
    .edma-solicitud-exito {
        min-height: 100vh;
        padding: 150px 0 80px;
        background: #f5f7fb;
    }

    .edma-solicitud-exito__card {
        max-width: 850px;
        margin: 0 auto;
        padding: 48px;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 18px 50px rgba(9, 34, 75, 0.08);
        border: 1px solid rgba(12, 48, 94, 0.08);
    }

    .edma-solicitud-exito__icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
        font-size: 2.2rem;
    }

    .edma-solicitud-exito__title {
        margin-bottom: 16px;
        color: #0b2348;
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 700;
        text-align: center;
    }

    .edma-solicitud-exito__intro {
        max-width: 650px;
        margin: 0 auto 32px;
        color: #536176;
        font-size: 1.05rem;
        line-height: 1.7;
        text-align: center;
    }

    .edma-solicitud-exito__codigo {
        margin-bottom: 24px;
        padding: 22px 24px;
        border-radius: 16px;
        background: #f2f6fc;
        text-align: center;
    }

    .edma-solicitud-exito__codigo span {
        display: block;
        margin-bottom: 6px;
        color: #68758a;
        font-size: 0.9rem;
    }

    .edma-solicitud-exito__codigo strong {
        color: #0b2348;
        font-size: 1.35rem;
        letter-spacing: 0.03em;
    }

    .edma-solicitud-exito__alert {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 38px;
        padding: 18px 20px;
        border-radius: 14px;
        background: #fff8e8;
        color: #66521c;
    }

    .edma-solicitud-exito__alert i {
        margin-top: 2px;
        font-size: 1.25rem;
    }

    .edma-solicitud-exito__section {
        margin-bottom: 32px;
    }

    .edma-solicitud-exito__section h2,
    .edma-solicitud-exito__contacto h2 {
        margin-bottom: 22px;
        color: #0b2348;
        font-size: 1.35rem;
        font-weight: 700;
    }

    .edma-solicitud-exito__steps {
        display: grid;
        gap: 18px;
    }

    .edma-solicitud-exito__step {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .edma-solicitud-exito__step > span {
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0b2348;
        color: #ffffff;
        font-weight: 700;
    }

    .edma-solicitud-exito__step strong {
        display: block;
        margin-bottom: 4px;
        color: #182d4c;
    }

    .edma-solicitud-exito__step p {
        margin: 0;
        color: #647186;
        line-height: 1.6;
    }

    .edma-solicitud-exito__notice {
        margin-bottom: 34px;
        padding: 20px 22px;
        border-left: 4px solid #d6a836;
        border-radius: 12px;
        background: #fbf8f0;
        color: #4f5663;
    }

    .edma-solicitud-exito__notice strong {
        display: block;
        margin-bottom: 6px;
        color: #0b2348;
    }

    .edma-solicitud-exito__contacto {
        padding-top: 8px;
        text-align: center;
    }

    .edma-solicitud-exito__contacto p {
        max-width: 620px;
        margin: 0 auto 22px;
        color: #647186;
        line-height: 1.6;
    }

    .edma-solicitud-exito__contact-buttons {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .edma-solicitud-exito__btn-primary,
    .edma-solicitud-exito__btn-secondary {
        padding: 11px 22px;
        border-radius: 10px;
        font-weight: 600;
    }

    .edma-solicitud-exito__btn-primary {
        background: #0b2348;
        border-color: #0b2348;
        color: #ffffff;
    }

    .edma-solicitud-exito__btn-primary:hover {
        background: #071a36;
        border-color: #071a36;
        color: #ffffff;
    }

    .edma-solicitud-exito__btn-secondary {
        background: #ffffff;
        border: 1px solid #cfd6e2;
        color: #0b2348;
    }

    .edma-solicitud-exito__btn-secondary:hover {
        background: #f3f5f8;
        color: #0b2348;
    }

    @media (max-width: 767.98px) {
        .edma-solicitud-exito {
            padding: 125px 0 50px;
        }

        .edma-solicitud-exito__card {
            padding: 30px 20px;
            border-radius: 18px;
        }

        .edma-solicitud-exito__codigo strong {
            font-size: 1.05rem;
            word-break: break-word;
        }
    }
</style>

@endsection