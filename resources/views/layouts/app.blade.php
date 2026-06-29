<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hidrosuroeste - Gestión Comunitaria</title>
    
    <!-- Bootstrap 5 & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Iconos Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --primary: #0c4271;
            --secondary: #0077b6;
            --bg-light: #f4f7f6;
            --text-dark: #333;
            --white: #ffffff;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-color: var(--bg-light);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            color: var(--white);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header img {
            width: 70px;
            border-radius: 12px;
            background: white;
            padding: 5px;
            margin-bottom: 10px;
        }

        .sidebar-header h6 {
            font-size: 0.9rem;
            letter-spacing: 1px;
            margin: 0;
            font-weight: 700;
        }

        .nav-container {
            flex: 1;
            overflow-y: auto;
            padding: 15px 0;
        }

        .nav-group {
            margin-bottom: 15px;
        }

        .nav-label {
            padding: 10px 25px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.5);
            display: block;
            font-weight: 600;
        }

        .nav-item {
            cursor: pointer;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: var(--white);
            text-decoration: none;
            transition: background 0.2s;
            justify-content: space-between;
            font-size: 0.88rem;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: var(--white);
        }

        .nav-link div {
            display: flex;
            align-items: center;
        }

        .nav-link i {
            margin-right: 12px;
            width: 18px;
            height: 18px;
        }

        /* Dropdown Logic */
        .dropdown-content {
            background: rgba(0,0,0,0.15);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .nav-item.open .dropdown-content {
            max-height: 500px;
        }

        .dropdown-link {
            padding: 10px 25px 10px 55px;
            display: block;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
        }

        .dropdown-link:hover, .dropdown-link.active {
            color: var(--white);
            background: rgba(255,255,255,0.05);
            padding-left: 60px;
        }

        .chevron {
            transition: transform 0.3s;
            width: 14px !important;
            height: 14px !important;
        }

        .nav-item.open .chevron {
            transform: rotate(90deg);
        }

        /* Main Content Area */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Evita scroll doble */
        }

        .topbar {
            height: 65px;
            background: var(--white);
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            flex-shrink: 0;
        }

        .content-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
        }

        /* Estilo para las tablas y formularios dentro del panel */
        .panel-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: none;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/aEEQCQI7_400x400.jpg') }}" alt="Logo" width="150">
            <h6>HIDROSUROESTE</h6>
        </div>

        <div class="nav-container">
            <!-- Inicio -->
            <div class="nav-group">
                <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <div><i data-lucide="layout-dashboard"></i> Dashboard</div>
                </a>
            </div>

            <!-- Grupo 1: Territorial -->
            <div class="nav-group">
                <span class="nav-label">Territorial y Social</span>
                <div class="nav-item {{ request()->is('municipios*') ? 'open' : '' }}">
                    <div class="nav-link" onclick="toggleDropdown(this)">
                        <div><i data-lucide="map"></i> Gestión Territorial</div>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="{{ route('municipios.index') }}" class="dropdown-link {{ request()->is('municipios*') ? 'active' : '' }}">Municipios</a>
                        <a href="{{ route('parroquias.index') }}" class="dropdown-link {{ request()->is('parroquias*') ? 'active' : '' }}">Parroquias</a>
                        <a href="{{ route('comunidades.index') }}" class="dropdown-link {{ request()->is('comunidades*') ? 'active' : '' }}">Comunidades</a>
                        <a href="{{ route('comunas.index') }}" class="dropdown-link {{ request()->is('comunas*') ? 'active' : '' }}">Comunas</a>
                    </div>
                </div>
            </div>

            <!-- Grupo 2: Organización -->
            <div class="nav-group">
                <span class="nav-label">Organización Social</span>
                <div class="nav-item">
                    <div class="nav-link" onclick="toggleDropdown(this)">
                        <div><i data-lucide="droplet"></i> Entidades Relacionadas</div>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="{{ route('centros-asociados.index') }}" class="dropdown-link {{ request()->is('centros-asociados*') ? 'active' : '' }}">Centros Educativos</a>
                        <a href="{{ route('consejos-comunales.index') }}" class="dropdown-link {{ request()->is('consejoscomunales*') ? 'active' : '' }}">Consejos Comunales</a>
                    </div>
                </div>
            </div>

            <!-- Grupo 3: Procesos -->
            <div class="nav-group">
                <span class="nav-label">Procesos del Sistema</span>
                <div class="nav-item">
                    <div class="nav-link" onclick="toggleDropdown(this)">
                        <div><i data-lucide="settings"></i> Mesas Técnicas</div>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="{{ route('mesas-tecnicas.index') }}" class="dropdown-link {{ request()->is('mesas-tecnicas*') ? 'active' : '' }}">Registro MTA</a>
                        <a href="{{ route('voceros.index') }}" class="dropdown-link {{ request()->is('voceros*') ? 'active' : '' }}">Voceros</a>
                        <a href="{{ route('mesas-historial.index') }}" class="dropdown-link {{ request()->is('mesas-historial*') ? 'active' : '' }}">Historial de MTA</a>
                        <a href="{{ route('voceros-historial.index') }}" class="dropdown-link {{ request()->is('voceros-historial*') ? 'active' : '' }}">Historial de Voceros</a>
                    </div>
                </div>
            </div> 

            <!-- Grupo 4: Gestión -->
            <div class="nav-group">
                <span class="nav-label">Proyectos y Control</span>
                <a href="{{ route('proyectos.index') }}" class="nav-link {{ request()->is('proyectos*') ? 'active' : '' }}"><div><i data-lucide="clipboard-list"></i> Proyectos</div></a>
                <a href="{{ route('incidencias.index') }}" class="nav-link {{ request()->is('incidencias*') ? 'active' : '' }}"><div><i data-lucide="alert-circle"></i> Incidencias</div></a>
                {{-- <a href="{{ route('documentos.index') }}" class="nav-link {{ request()->is('documentos*') ? 'active' : '' }}"><div><i data-lucide="file-text"></i> Documentación</div></a> --}}
            </div>

            <!-- Grupo 5: Sistema -->
            <div class="nav-group">
                <span class="nav-label">Configuración</span>
                <a href="{{ route('usuarios.index') }}" class="nav-link"><div><i data-lucide="users"></i> Usuarios</div></a>
                {{-- <a href="{{ route('bitacoras.index') }}" class="nav-link"><div><i data-lucide="database"></i> Bitácora</div></a> --}}
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="main">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <h5 class="m-0 fw-bold text-muted" style="font-size: 1rem;">Panel de Administración - Gerencia Comunitaria</h5>
            </div>
            <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <div class="bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:32px; height:32px; font-size: 0.8rem;">
                        {{ substr(Auth::user()->nombre ?? 'A', 0, 1) }}
                    </div>
                    <span class="small fw-bold">{{ Auth::user()->nombre ?? 'Usuario' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger small">Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <div class="content-scroll">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        lucide.createIcons();

        function toggleDropdown(element) {
            const parent = element.parentElement;
            parent.classList.toggle('open');
        }
    </script>
</body>
</html>