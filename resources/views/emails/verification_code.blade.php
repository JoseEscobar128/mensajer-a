<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            max-width: 90%;
            width: 100%;
            padding: 20px;
        }

        .verification-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .verification-container h1 {
            font-size: 28px;
            font-weight: 500;
            color: #333;
            margin-bottom: 20px;
        }

        .verification-container p {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }

        .verification-code {
            font-size: 36px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 30px;
        }

        .footer {
            font-size: 14px;
            color: #999;
        }

        @media (min-width: 768px) {
            .container {
                max-width: 600px;
            }
        }

        @media (min-width: 1024px) {
            .container {
                max-width: 500px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-container">
            <h1>Tu Código de Verificación</h1>
            <p>Usa el siguiente código para completar tu inicio de sesión:</p>
            <div class="verification-code">{{ $verificationCode }}</div>
            <p class="footer">Este código es válido por 10 minutos.</p>
        </div>
    </div>
</body>
</html>
