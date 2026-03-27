<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de acceso</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding:20px 0;">
        <tr>
            <td align="center">

                <!-- CONTENEDOR -->
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden;">

                    <!-- HEADER -->
                    <tr>
                        <td style="background:#e5e7ee; padding:15px 20px;">
                            <table width="100%">
                                <tr>
                                    <td align="left">
                                        <img src="https://res.cloudinary.com/dqvnk6ll2/image/upload/f_auto,q_auto,w_200/logo_c_kipjv9.png" alt="Logo Empresa" height="40">
                                    </td>
                                    <td align="right">
                                        <img src="https://res.cloudinary.com/dqvnk6ll2/image/upload/f_auto,q_auto,w_200/meipel_adclvd.png" alt="App Logo" height="40">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding:25px; color:#333; font-size:14px; line-height:1.6;">

                            <p>Buen día,</p>

                            <p>
                                Espero que se encuentren bien. El motivo de este correo es solicitar acceso al servidor con la siguiente información:
                            </p>

                            <!-- INFO BOX -->
                            <table width="100%" cellpadding="8" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:6px;">
                                <tr>
                                    <td><strong>Referencia:</strong></td>
                                    <td>GAIA-{{ $requestData->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>IP del servidor:</strong></td>
                                    <td>{{ $requestData->server->ip_address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Hostname:</strong></td>
                                    <td>{{ $requestData->server->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Responsable:</strong></td>
                                    <td>{{ $requestData->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Motivo:</strong></td>
                                    <td>{{ $requestData->reason }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Horario:</strong></td>
                                    <td>
                                        {{ $requestData->start_at->format('d/m/Y H:i') }}
                                        @if($requestData->end_at)
                                            hasta {{ $requestData->end_at->format('H:i') }}
                                        @else
                                            hasta (sin horario definido)
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top:20px;">
                                Quedo atento a cualquier duda o comentario adicional.
                            </p>

                            <p style="margin-top:30px;">
                                Saludos cordiales,<br>
                                <strong>{{ $requestData->user->name }}</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background:#f1f5f9; padding:15px; text-align:center; font-size:12px; color:#666;">
                            Correo fue generado automáticamente por GAIA.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>