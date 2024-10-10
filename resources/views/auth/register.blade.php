<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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

        .register-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 500;
            color: #333;
        }

        .register-container label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .register-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .register-container input:focus {
            border-color: #007bff;
            outline: none;
        }

        .register-container button {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .register-container button:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive Design */
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
        <div class="register-container">
            <h2>Registro de Usuario</h2>

            <!-- Mostrar mensajes de éxito -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Mostrar errores de validación -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Mostrar mensaje de error general -->
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <label for="name">Nombre</label>
                    <input type="text" name="name" required value="{{ old('name') }}">
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>
