<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nuevo mensaje de contacto</title>
</head>
<body>
<h2>Nuevo mensaje desde el formulario de contacto</h2>

<p><strong>Nombre:</strong> {{ $data['name'] }}</p>
<p><strong>Correo:</strong> {{ $data['email'] }}</p>
<p><strong>Teléfono:</strong> {{ $data['phone'] }}</p>
<p><strong>Mensaje:</strong></p>
<p>{{ $data['message'] }}</p>

<hr>
<p>Enviado desde la web de Finca 3 Pinos.</p>
</body>
</html>
