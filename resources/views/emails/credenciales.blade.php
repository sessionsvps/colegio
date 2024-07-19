<!DOCTYPE html>
<html>

<head>
    <title>Credenciales de Acceso</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap');

        body {
            background-color: #f7fafc;
            font-family: 'Lora', serif;
            line-height: 1.6;
            color: #2d3748;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 100px;
            height: 100px;
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #000000;
            /* Color negro para el título */
        }

        .content p {
            margin: 10px 0;
        }

        .content strong {
            color: #2d3748;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ $message->embed(public_path('img/sideral-logo.jpg')) }}" alt="Logo" class="logo">
        <h1 class="title">Sideral Carrión</h1>
        <div class="content">
            <p>Estimado/a Docente,</p>
            <p>Se han generado sus credenciales de acceso:</p>
            <p><strong>Correo:</strong> {{ $email }}</p>
            <p><strong>Contraseña:</strong> {{ $password }}</p>
            <p>Por favor, cambie su contraseña después de iniciar sesión por primera vez.</p>
            <p>Saludos,</p>
            <p>El Equipo de Sideral Carrión</p>
        </div>
    </div>
</body>

</html>