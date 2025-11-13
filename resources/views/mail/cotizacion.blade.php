<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nuevo mensaje de cotización</title>
</head>
<body>
<h2>Nuevo mensaje desde el formulario de cotización</h2>

<p><strong>Nombre:</strong> {{ $data['nombre'] }}</p>
<p><strong>Correo:</strong> {{ $data['email'] }}</p>
<p><strong>Teléfono:</strong> {{ $data['telefono'] }}</p>
<p><strong>Mensaje:</strong></p>
<p>{{ $data['mensaje'] }}</p>

<hr>
<p>Enviado desde la web de Finca 3 Pinos.</p>
</body>
</html>
