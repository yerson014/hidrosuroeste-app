<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Registro de Cuenta — Hidrosuroeste</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #0c4271;
            --secondary: #0077b6;
            --accent: #10b981;
            --bg: #f1f5f9;
            --text-dark: #1e293b;
        }
        body { 
            margin: 0; 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
        }
        .header { text-align: center; margin-bottom: 30px; }
        .header img { width: 80px; border-radius: 15px; margin-bottom: 15px; }
        .header h2 { margin: 0; color: var(--primary); font-size: 1.5rem; }
        .header p { color: #64748b; margin-top: 5px; font-size: 0.9rem; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .full-width { grid-column: span 2; }

        .form-group { margin-bottom: 15px; }
        .form-group label { 
            display: block; 
            margin-bottom: 5px; 
            font-size: 0.85rem; 
            font-weight: 600; 
            color: var(--text-dark);
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        .btn-register:hover { background: var(--secondary); }

        .footer-links {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #64748b;
        }
        .footer-links a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.8rem;
        }
        .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="header">
            <img src="{{ asset('images/aEEQCQI7_400x400.jpg') }}" alt="Logo" width="300">
            <h2>Crear Cuenta</h2>
            <p>Únase al sistema de Gestión Comunitaria</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-grid">
                <!-- Nombre -->
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Juan">
                </div>

                <!-- Apellido -->
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}" required placeholder="Ej: Pérez">
                </div>

                <!-- Cédula -->
                <div class="form-group">
                    <label for="cedula">Cédula de Identidad</label>
                    <input type="text" id="cedula" name="cedula" value="{{ old('cedula') }}" required placeholder="V-00000000">
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                </div>

                <!-- Email -->
                <div class="form-group full-width">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="usuario@correo.com">
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-register">Registrar Usuario</button>
        </form>

        <div class="footer-links">
            ¿Ya tiene una cuenta? <a href="{{ route('login') }}">Inicie Sesión</a>
        </div>
    </div>
</body>
</html>