<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Acceso — Hidrosuroeste</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #0c4271;
            --secondary: #0077b6;
            --accent: #10b981;
            --bg: #f8fafc;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        body::before{
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%230077b6' fill-opacity='0.1'%3E%3Cpath d='M40 80a40 40 0 1 1 0-80 40 40 0 0 1 0 80zm0-44a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM20 60a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm40-40a4 4 0 1 0 0 8 4 4 0 0 0 0-8z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .brand img {
            height: 80px;
            margin-bottom: 20px;
        }
        h2 { color: var(--primary); margin-bottom: 10px; }
        p { color: #64748b; font-size: 0.9rem; margin-bottom: 30px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #1e293b; }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-sizing: border-box;
            transition: all 0.3s;
        }
        input:focus { outline: none; border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1); }
        .btn {
            width: 100%;
            padding: 12px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: opacity 0.3s;
        }
        .btn:hover { opacity: 0.9; }
        .footer-links { margin-top: 20px; font-size: 0.85rem; }
        .footer-links a { color: var(--secondary); text-decoration: none; font-weight: 600; }
        .alert { background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <img src="{{ asset('images/aEEQCQI7_400x400.jpg') }}" alt="Logo" width="150" height="150">
        </div>
        <h2>Acceso al Sistema</h2>
        <p>Gestión de Mesas Técnicas del Agua</p>

        @if ($errors->any())
            <div class="alert">Credenciales incorrectas. Intente de nuevo.</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" required placeholder="ejemplo@hidrosuroeste.com">
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="contraseña" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn">
                <i data-lucide="log-in" size="18"></i> Ingresar al Sistema
            </button>
        @csrf </form>

        {{-- <div class="footer-links">
            ¿No tiene una cuenta? <a href="{{ route('register') }}">Regístrese aquí</a>
        </div> --}}
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>