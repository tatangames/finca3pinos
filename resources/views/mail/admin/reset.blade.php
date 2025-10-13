<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Restablecer contraseña</title>

    <!-- Reset básico y helpers -->
    <style>
        html, body { margin:0 !important; padding:0 !important; height:100% !important; width:100% !important; }
        * { -ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt !important; mso-table-rspace:0pt !important; }
        img { -ms-interpolation-mode:bicubic; }
        a { text-decoration:none; }
        /* iOS “blue links” */
        a[x-apple-data-detectors], .unstyle-auto-detected-links a, .aBn { color:inherit !important; text-decoration:none !important; border-bottom:0 !important; cursor:default !important; }
        /* Gmail dark mode fix (suave) */
        @media (prefers-color-scheme: dark) {
            .bg-body { background:#121212 !important; }
            .bg-card { background:#1c1c1c !important; }
            .text-muted { color:#bbbbbb !important; }
        }
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container { width:100% !important; }
            .px-40 { padding-left:20px !important; padding-right:20px !important; }
            .btn > span { display:block !important; }
        }
    </style>

    <!-- Fix Outlook -->
    <!--[if mso]>
    <style>
        body, table, td, a { font-family: Arial, Helvetica, sans-serif !important; }
    </style>
    <![endif]-->
</head>

<body class="bg-body" style="margin:0; padding:0; background:#f5f5f5;">
<!-- Preheader (texto de vista previa) -->
<div style="display:none; visibility:hidden; opacity:0; color:transparent; height:0; width:0; overflow:hidden; mso-hide:all;">
    Solicitud para restablecer tu contraseña en {{ $marca }}. Si no fuiste tú, ignora este correo.
</div>

<!-- Wrapper -->
<table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#f5f5f5;">
    <tr>
        <td align="center" style="padding:24px;">
            <!-- Card -->
            <table role="presentation" class="container" width="600" border="0" cellspacing="0" cellpadding="0" style="width:600px; max-width:600px; background:#ffffff;" >
                <!-- Header con logo -->
                <tr>
                    <td align="center" style="padding:24px;">
                        <img src="{{ asset('images/logoindex.png') }}" width="160" alt="{{ $marca }}" style="border:0; display:block; max-width:160px; height:auto;">
                    </td>
                </tr>

                <!-- Banda dorada con icono -->
                <tr>
                    <td align="center" style="background:#000000; padding:24px 16px;">
                        <img src="{{ asset('images/candadoemail.png') }}" width="48" height="48" alt="Candado" style="border:0; display:block; margin:0 auto 8px;">
                        <div style="font-family:Arial,Helvetica,sans-serif; font-size:24px; line-height:1.3; color:#ffffff; font-weight:bold;">
                            Restablecer tu contraseña
                        </div>
                    </td>
                </tr>

                <!-- Contenido -->
                <tr>
                    <td class="px-40" style="padding:32px 40px 8px; font-family:Arial,Helvetica,sans-serif; color:#444; font-size:16px; line-height:1.6;">
                        <p style="margin:0 0 12px;">
                            Hola{{ isset($nombre) && $nombre ? ' ' . e($nombre) : '' }},
                        </p>
                        <p style="margin:0 0 12px;">
                            Hemos recibido una solicitud para restablecer tu contraseña en <strong>{{ $marca }}</strong>.
                        </p>
                        <p style="margin:0 0 20px;">
                            Para continuar, haz clic en el botón siguiente:
                        </p>
                    </td>
                </tr>

                <!-- Botón (bulletproof) -->
                <tr>
                    <td class="px-40" style="padding:0 40px 24px;">
                        <!--[if mso]>
              <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="{{ $url }}" style="height:48px;v-text-anchor:middle;width:260px;" arcsize="6%" stroke="f" fillcolor="#18163A">
                <w:anchorlock/>
                <center style="color:#FFFFFF;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:bold;">Restablecer contraseña</center>
              </v:roundrect>
              <![endif]-->

                        <a href="{{ $url }}" target="_blank"
                           class="btn"
                           style="background:#000000; color:#ffffff; display:inline-block; border-radius:4px; font-family:Arial,Helvetica,sans-serif; font-size:16px; font-weight:bold; line-height:48px; text-align:center; min-width:260px;">
                            <span style="padding:0 24px; display:inline-block;">Restablecer contraseña</span>
                        </a>
                        <!--<![endif]-->
                    </td>
                </tr>

                <!-- Enlace plano -->
                <tr>
                    <td class="px-40" style="padding:0 40px 24px; font-family:Arial,Helvetica,sans-serif; color:#666; font-size:14px; line-height:1.6;">
                        <p style="margin:0 0 8px;">
                            Si el botón no funciona, copia y pega este enlace en tu navegador:
                        </p>
                        <p style="margin:0;">
                            <a href="{{ $url }}" style="color:#18163A; word-break:break-all;">{{ $url }}</a>
                        </p>
                    </td>
                </tr>

                <!-- Aviso -->
                <tr>
                    <td class="px-40" style="padding:0 40px 32px; font-family:Arial,Helvetica,sans-serif; color:#888; font-size:13px; line-height:1.6; font-style:italic;">
                        Si tú no realizaste esta solicitud, puedes ignorar este correo.
                    </td>
                </tr>

                <!-- Footer dorado con contacto -->
                <tr>
                    <td style="background:#000000; padding:20px 24px;">
                        <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td valign="top" style="font-family:Arial,Helvetica,sans-serif; color:#F2F2F2; font-size:13px; line-height:1.5;">
                                    <strong style="color:#ffffff;">Contacto</strong><br>
                                    El Pinar, Cantón Montenegro, Metapán, Santa Ana Norte.<br>
                                    (+503) 7620-6851 · <a href="mailto:info@finca3pinos.com" style="color:#ffffff;">info@finca3pinos.com</a>
                                </td>
                                <td align="right" valign="top" style="font-family:Arial,Helvetica,sans-serif;">
                                    <a href="https://facebook.com/" target="_blank" style="display:inline-block;">
                                        <img src="{{ asset('images/logos/facebook.png') }}" width="28" height="28" alt="Facebook" style="border:0; display:block;">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Copyright -->
                <tr>
                    <td align="center" style="padding:14px 24px 24px; font-family:Arial,Helvetica,sans-serif; color:#9a9a9a; font-size:12px;">
                        © 2025 {{ $marca }} — Todos los derechos reservados.
                    </td>
                </tr>
            </table>
            <!-- /Card -->
        </td>
    </tr>
</table>
</body>
</html>
