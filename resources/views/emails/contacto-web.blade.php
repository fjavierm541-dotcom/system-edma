<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>Nuevo mensaje desde EDMA Web</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background: #f4f6f9;
    font-family: Arial, Helvetica, sans-serif;
    color: #1f2937;
">

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    role="presentation"
    style="padding: 35px 15px;"
>
    <tr>
        <td align="center">

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                role="presentation"
                style="
                    max-width: 650px;
                    background: #ffffff;
                    border-radius: 14px;
                    overflow: hidden;
                "
            >

                <tr>
                    <td style="
                        padding: 28px 32px;
                        background: #001749;
                        color: #ffffff;
                    ">

                        <div style="
                            color: #ffb51c;
                            font-size: 12px;
                            font-weight: bold;
                            text-transform: uppercase;
                        ">
                            EDMA Web
                        </div>

                        <h1 style="
                            margin: 7px 0 0;
                            font-size: 24px;
                        ">
                            Nuevo mensaje de contacto
                        </h1>

                    </td>
                </tr>


                <tr>
                    <td style="padding: 32px;">

                        <p style="
                            margin: 0 0 25px;
                            color: #667085;
                            font-size: 15px;
                            line-height: 1.6;
                        ">
                            Una persona ha enviado una consulta desde el
                            formulario de contacto de Edumerican Academy.
                        </p>


                        <p style="margin: 0 0 18px;">
                            <strong>Nombre</strong><br>

                            {{ $nombre }}
                        </p>


                        <p style="margin: 0 0 18px;">
                            <strong>Correo electrónico</strong><br>

                            {{ $correo }}
                        </p>


                        <p style="margin: 0 0 25px;">
                            <strong>Asunto</strong><br>

                            {{ $asunto }}
                        </p>


                        <div style="
                            padding: 20px;
                            border-radius: 10px;
                            background: #f4f6f9;
                        ">

                            <strong>Mensaje</strong>

                            <p style="
                                margin: 10px 0 0;
                                line-height: 1.7;
                                white-space: pre-line;
                            ">{{ $mensaje }}</p>

                        </div>

                    </td>
                </tr>


                <tr>
                    <td style="
                        padding: 20px 32px;
                        border-top: 1px solid #eeeeee;
                        color: #8a94a6;
                        font-size: 12px;
                    ">

                        Mensaje enviado desde el sitio web de
                        Edumerican Academy Honduras.

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>