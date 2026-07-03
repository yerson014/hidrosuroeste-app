<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hidrosuroeste - Gestión Comunitaria</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('images/aEEQCQI7_400x400.jpg') }}?v=1" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            height: 100vh;
            top: 0;
            left: 0;
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

        .main-content {
            margin-left: var(--sidebar-width); /* Deja el espacio para la barra */
            padding: 20px;
            width: calc(100% - var(--sidebar-width));
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

        /* Estilos Personalizados para el Manual de Usuario Interactivo */
        .manual-nav .nav-link {
            color: #4a5568;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 8px;
            display: block;
            align-items: center;
            font-weight: 500;
        }
        .manual-nav .nav-link:hover {
            background-color: #f7fafc;
            color: var(--primary);
        }
        .manual-nav .nav-link.active {
            background-color: #ebf8ff;
            color: var(--secondary) !important;
            font-weight: 600;
        }
        .manual-content-box {
            max-height: 65vh;
            overflow-y: auto;
            padding-right: 10px;
        }

        @media (min-width: 769px) {
            #sidebarToggle {
                display: none !important;
            }
        }

            /* NUEVO: Responsive Design para Sidebar */
        @media (max-width: 992px) {
            .content-scroll {
                width: 100% !important;
                margin-left: 0 !important;
            }
            header {
                padding: 10px 15px !important;
            }
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                width: 280px;
                height: 100vh;
                transition: left 0.3s ease-in-out;
                z-index: 2000;
                display: none;
            }
            .sidebar.active {
                left: 0; /* Aparece al presionar el botón */
            }
            .main { width: 100%; }
            .main-content {
                margin-left: 0;
                width: 100%;
            }

            
        }

        .content-scroll { flex: 1; overflow-y: auto; padding: 20px; }
    </style>
</head>
<body>

    <aside class="sidebar d-flex flex-column">
        <button class="btn btn-link text-white d-md-none align-self-end p-3" id="sidebarClose">
            <i data-lucide="x"></i>
        </button>
        <div class="sidebar-header">
            <img src="{{ asset('images/aEEQCQI7_400x400.jpg') }}" alt="Logo" width="150">
            <h6 class="text-white mb-4">HIDROSUROESTE</h6>
        </div>

        <div class="nav-container">
            <div class="nav-group">
                <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <div><i data-lucide="layout-dashboard"></i> Dashboard</div>
                </a>
            </div>

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

            <div class="nav-group">
                <span class="nav-label">Proyectos y Control</span>
                <a href="{{ route('proyectos.index') }}" class="nav-link {{ request()->is('proyectos*') ? 'active' : '' }}"><div><i data-lucide="clipboard-list"></i> Proyectos</div></a>
                <a href="{{ route('incidencias.index') }}" class="nav-link {{ request()->is('incidencias*') ? 'active' : '' }}"><div><i data-lucide="alert-circle"></i> Incidencias</div></a>
            </div>

            <div class="nav-group">
                <span class="nav-label">Configuración</span>
                <a href="{{ route('usuarios.index') }}" class="nav-link"><div><i data-lucide="users"></i> Usuarios</div></a>
            </div>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <button class="btn btn-link d-md-none text-dark p-0 me-3" id="sidebarToggle">
                <i data-lucide="menu"></i>
            </button>
            <div class="d-flex align-items-center">
                <h5 class="m-0 fw-bold text-muted" style="font-size: calc(0.85rem + 0.3vw);">
                    Panel de Administración<span class="d-none d-sm-inline"> - Gerencia Comunitaria</span>
                </h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-warning d-flex align-items-center justify-center fw-bold btn-sm px-2 px-sm-3 shadow-sm text-dark" data-bs-toggle="modal" data-bs-target="#manualUsuarioModal" style="border-radius: 8px;">
                    <!-- Icono: quitamos 'me-2' y usamos 'me-sm-2' para que el margen solo exista cuando hay texto -->
                    <i data-lucide="help-circle" class="me-sm-2" style="width: 18px; height: 18px;"></i> 
                    <!-- Texto: se oculta en móviles con 'd-none' y se muestra desde pantallas pequeñas en adelante con 'd-sm-inline' -->
                    <span class="d-none d-sm-inline">Manual de Ayuda</span>
                </button>

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
            </div>
        </header>

        <div class="content-scroll">
            @yield('content')
        </div>
    </div>

    <div class="modal fade" id="manualUsuarioModal" tabindex="1" aria-labelledby="manualUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header text-white bg-primary py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title d-flex align-items-center" id="manualUsuarioModalLabel">
                        <i data-lucide="book-open" class="me-2"></i> Manual de Usuario - Mesas Técnicas del Agua (MTA) Hidrosuroeste
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-0">
                    <div class="row g-0 h-100">
                        <div class="col-md-3 bg-white p-3 border-end manual-nav" style="min-height: 500px; max-height: 65vh; overflow-y: auto;">
                            <div class="text-uppercase text-muted fw-bold small mb-2 px-2" style="font-size: 0.75rem;"> MÓDULOS DEL SISTEMA </div>
                            <div class="nav flex-column nav-pills mb-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active text-start border-0 bg-transparent py-2 small" id="v-pills-login-tab" data-bs-toggle="pill" data-bs-target="#v-pills-login" type="button" role="tab"><i data-lucide="lock" class="me-2" style="width:14px;"></i> Login / Acceso</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-dash-tab" data-bs-toggle="pill" data-bs-target="#v-pills-dash" type="button" role="tab"><i data-lucide="layout-dashboard" class="me-2" style="width:14px;"></i> Dashboard</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-muni-tab" data-bs-toggle="pill" data-bs-target="#v-pills-muni" type="button" role="tab"><i data-lucide="map-pin" class="me-2" style="width:14px;"></i> Municipios</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-parr-tab" data-bs-toggle="pill" data-bs-target="#v-pills-parr" type="button" role="tab"><i data-lucide="map" class="me-2" style="width:14px;"></i> Parroquias</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-comu-tab" data-bs-toggle="pill" data-bs-target="#v-pills-comu" type="button" role="tab"><i data-lucide="home" class="me-2" style="width:14px;"></i> Comunidades</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-comunas-tab" data-bs-toggle="pill" data-bs-target="#v-pills-comunas" type="button" role="tab"><i data-lucide="globe" class="me-2" style="width:14px;"></i> Comunas</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-centros-tab" data-bs-toggle="pill" data-bs-target="#v-pills-centros" type="button" role="tab"><i data-lucide="building" class="me-2" style="width:14px;"></i> Centros Educativos</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-consejos-tab" data-bs-toggle="pill" data-bs-target="#v-pills-consejos" type="button" role="tab"><i data-lucide="users-2" class="me-2" style="width:14px;"></i> Consejos Comunales</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-mta-tab" data-bs-toggle="pill" data-bs-target="#v-pills-mta" type="button" role="tab"><i data-lucide="droplet" class="me-2" style="width: 12px; height: 12px;"></i> Mesas Técnicas del Agua (MTA)</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-voceros-tab" data-bs-toggle="pill" data-bs-target="#v-pills-voceros" type="button" role="tab"><i data-lucide="user-check" class="me-2" style="width:14px;"></i> Voceros</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-hist-mta-tab" data-bs-toggle="pill" data-bs-target="#v-pills-hist-mta" type="button" role="tab"><i data-lucide="history" class="me-2" style="width:14px;"></i> Historial de MTA</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-hist-voc-tab" data-bs-toggle="pill" data-bs-target="#v-pills-hist-voc" type="button" role="tab"><i data-lucide="folder-clock" class="me-2" style="width:14px;"></i> Historial de Voceros</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-proy-tab" data-bs-toggle="pill" data-bs-target="#v-pills-proy" type="button" role="tab"><i data-lucide="file-spreadsheet" class="me-2" style="width:14px;"></i> Proyectos</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-inci-tab" data-bs-toggle="pill" data-bs-target="#v-pills-inci" type="button" role="tab"><i data-lucide="alert-triangle" class="me-2" style="width:14px;"></i> Incidencias</button>
                                <button class="nav-link text-start border-0 bg-transparent py-2 small" id="v-pills-user-tab" data-bs-toggle="pill" data-bs-target="#v-pills-user" type="button" role="tab"><i data-lucide="users" class="me-2" style="width:14px;"></i> Usuarios</button>
                            </div>
                        </div>

                        <div class="col-md-9 p-4 bg-white" style="max-height: 65vh; overflow-y: auto;">
                            <div class="tab-content manual-content-box" id="v-pills-tabContent">
                                
                                <div class="tab-pane fade show active" id="v-pills-login" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Autenticación (Login)</h4>
                                    <p class="text-secondary leading-relaxed">Para acceder al sistema, debes ingresar el correo electrónico y la contraseña asignada en la pantalla de inicio. Una vez que los datos son validados correctamente por los servidores de seguridad, presionas el botón <strong>Iniciar Sesión</strong> para entrar de forma segura a la plataforma de gestión.</p>
                                    <div class="alert alert-info d-flex align-items-center mt-3"><i data-lucide="info" class="me-2"></i> Recuerde no compartir sus credenciales administrativas bajo ninguna circunstancia.</div>
                                </div>

                                <div class="tab-pane fade" id="v-pills-dash" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Dashboard / Panel Principal</h4>
                                    <p class="text-secondary leading-relaxed">Al acceder satisfactoriamente al sistema, se visualizará el Dashboard o panel principal. Aquí podrás consultar de forma rápida la información general relacionada con las <strong>Mesas Técnicas de Agua (MTA)</strong>, proyectos e incidencias operativas activas, además de acceder a los diferentes módulos disponibles mediante el menú de navegación.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-muni" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Gestión de Municipios</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite gestionar los municipios registrados dentro del sistema.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Registrar Municipio:</strong> Para registrar un municipio, haz click en el boton <code>Nuevo Municipio</code>, seleccione el municipio correspondiente y presione el botón <code>Guardar Municipio</code>.</li>
                                        <li><strong>Listado de Municipios:</strong> Desde esta pantalla puedes consultar todos los municipios vigentes, realizar búsquedas, editar información existente o eliminar registros cuando sea necesario.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-parr" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Parroquias</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite gestionar las parroquias asociadas a cada municipio.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Municipio perteneciente:</strong> Municipio donde se encuentra ubicada la parroquia.</li>
                                        <li><strong>Nombre de la Parroquia:</strong> Nombre que identificará la parroquia dentro del sistema.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar una parroquia, haz click en el boton <code>Nueva Parroquia</code>, debes seleccionar el municipio al que pertenece y luego indicar el nombre de la parroquia de acuerdo al municipio seleccionado y presionar el botón <code>Guardar Parroquia</code>.</p>
                                    <p class="text-secondary">En el listado de parroquias permite consultar las parroquias registradas y realizar búsquedas de información.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-comu" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Comunidades</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite registrar y administrar las comunidades asociadas a cada parroquia.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Parroquia perteneciente:</strong> Parroquia donde se encuentra ubicada la comunidad.</li>
                                        <li><strong>Nombre de la comunidad:</strong> Nombre de la comunidad.</li>
                                        <li><strong>Sector:</strong> Sector o zona a la que pertenece.</li>
                                        <li><strong>Usa cisterna:</strong> Especifica si recibe suministro de agua mediante cisterna.</li>
                                        <li><strong>Agua potable:</strong> Indica la disponibilidad del servicio de agua potable.</li>
                                        <li><strong>Zonas de silencio:</strong> Permite indicar si en esa comunidad no les llega suficiente agua potable.</li>
                                        <li><strong>Tanques grandes:</strong> Indica la existencia de sistemas de almacenamiento de agua.</li>
                                        <li><strong>Habitantes:</strong> Cantidad total de habitantes.</li>
                                        <li><strong>Familias:</strong> Número de familias.</li>
                                        <li><strong>Hombres:</strong> Número de Habitantes Masculinos.</li>
                                        <li><strong>Mujeres:</strong> Número de Habitantes Femeninos.</li>
                                        <li><strong>Niños:</strong> Número de Habitantes Niños.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar una <code>Nueva Comunidad</code> debes completar la información solicitada y presionar el botón <code>Guardar Comunidad</code>.</p>
                                    <p class="text-secondary">En la gestión de comunidades podrás consultar las comunidades registradas y realizar búsquedas de información.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-comunas" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Comunas</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite registrar y administrar las comunas existentes.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Parroquia perteneciente:</strong> Parroquia donde se encuentra ubicada la comuna.</li>
                                        <li><strong>Comunidad asociada:</strong> Comunidad relacionada con la comuna.</li>
                                        <li><strong>Nombre de la comuna:</strong> Nombre que identificará la comuna.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar una <code>Nueva Comuna</code> debes completar la información solicitada y presionar el botón <code>Guardar Comuna</code>.</p>
                                    <p class="text-secondary">En la gestión de comunas podrás consultar las comunas registradas dentro del sistema y realizar búsquedas de información.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-centros" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Centros Educativos</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite registrar instituciones educativas asociadas a las comunidades.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Comunidad perteneciente:</strong> Comunidad donde se encuentra ubicada la institución.</li>
                                        <li><strong>Tipo de Centro:</strong> Clasificación de la institución educativa.</li>
                                        <li><strong>Nombre de la escuela:</strong> Nombre de la institución.</li>
                                        <li><strong>Dirección o Ubicación Técnica:</strong> Ubicación física de la institución.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar un <code>Nuevo Centro</code> debes completar los datos solicitados y luego presionar el botón <code>Guardar Centro</code>.</p>
                                    <p class="text-secondary">En la gestión de comunas podrás consultar las comunas registradas dentro del sistema y realizar búsquedas de información.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-consejos" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Consejos Comunales</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite registrar y administrar los consejos comunales asociados a cada comunidad.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Comunidad perteneciente:</strong> Comunidad donde funciona el consejo comunal.</li>
                                        <li><strong>Nombre de la consejo comunal:</strong> Nombre del consejo comunal.</li>
                                        <li><strong>Nombre del líder:</strong> Responsable o representante principal.</li>
                                        <li><strong>Teléfono de contacto:</strong> Número telefónico del responsable.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar un <code>Nuevo Consejo</code> debes completar la información requerida y luego presionar el botón <code>Guardar Consejo Comunal</code>.</p>
                                    <p class="text-secondary">En la gestión de consejos comunales podrás visualizar y consultar los consejos comunales registrados en el sistema y realizar búsquedas de información.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-mta" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Mesas Técnicas de Agua (MTA)</h4>
                                    <div style="display: block; width: 100%;">
                                    <p class="text-secondary leading-relaxed"><strong>Eje fundamental del sistema.</strong> Este módulo permite registrar y administrar las Mesas Técnicas de Agua asociadas a las comunidades.</p>
                                    <p class="text-secondary">Cada registro requiere la vinculación obligatoria a una comunidad o consejo comunal legalmente constituido, la declaración del número total de integrantes activos y el estatus actual de funcionamiento de la misma.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Nombre de la Mesa Técnica:</strong> Permite indicar el nombre que identificará la mesa técnica.</li>
                                        <li><strong>Consejo Comunal:</strong> Permite seleccionar el consejo comunal asociado.</li>
                                        <li><strong>Centro Educativo/Asociado (Opcional):</strong> Permite relacionar un centro educativo o institución vinculada.</li>
                                        <li><strong>Dirección Específica:</strong> Permite registrar la ubicación detallada de la mesa técnica.</li>
                                        <li><strong>Estado Operativo:</strong> Permite indicar si la mesa técnica se encuentra activa o en otra condición operativa.</li>
                                        <li><strong>Fecha de Constitución:</strong> Fecha en la que fue conformada la mesa técnica.</li>
                                        <li><strong>N° de Voceros (Integrantes):</strong> Cantidad de voceros asociados a la mesa técnica.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar una <code>Nueva Mesa</code> debes completar la información solicitada y posteriormente presionar el botón <code>Guardar Mesa Técnica</code>.</p>
                                    <p class="text-secondary">En la gestión de mesas técnicas puedes consultar las mesas técnicas registradas, realizar búsquedas, editar información existente o eliminar registros cuando sea necesario.</p>
                                        <div class="alert alert-warning d-flex align-items-center mt-3">
                                            <i data-lucide="alert-circle" class="me-2"></i> <strong>Regla de Validación:</strong> El sistema restringe la estructura a un máximo estricto de 12 integrantes (6 voceros principales y 6 voceros suplentes). No se admiten nombres duplicados.
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="v-pills-voceros" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Gestión de Voceros</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite registrar y administrar los voceros pertenecientes a las Mesas Técnicas de Agua.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Cédula:</strong> Número de identificación del vocero. </li>
                                        <li><strong>Nombre:</strong> Nombre del Vocero.</li>
                                        <li><strong>Apellido:</strong> Apellido del Vocero.</li>
                                        <li><strong>Género:</strong> Género del Vocero.</li>
                                        <li><strong>Teléfono:</strong> Número telefónico de contacto.</li>
                                        <li><strong>Estado:</strong> Permite indicar si el vocero se encuentra activo. </li>
                                        <li><strong>Dirección de Habitación:</strong> Dirección de residencia del vocero.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar un <code>Nuevo Vocero</code> debes completar la información solicitada y luego presionar el botón <code>Guardar Vocero</code>.</p>
                                    <p class="text-secondary">En la gestión de voceros podrás consultar todos los voceros registrados dentro del sistema, realizar búsquedas, editar información existente o eliminar registros cuando sea necesario.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-hist-mta" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Historial de Mesas Técnicas</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite llevar el control histórico de las actividades y eventos relacionados con las Mesas Técnicas de Agua.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Mesa Técnica:</strong> Permite seleccionar la mesa técnica relacionada con el evento.</li>
                                        <li><strong>Descripción del Evento:</strong> Permite registrar el detalle de la actividad, reunión, mantenimiento o cualquier acontecimiento relacionado.</li>
                                        <li><strong>Fecha y Hora del Evento:</strong> Permite indicar la fecha y hora en que ocurrió el evento.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar un <code>Nuevo Registro</code> o evento en el historial de las mesas técnicas, debes completar la información solicitada y luego presionar el botón <code>Guardar en Historial</code>.</p>
                                    <p class="text-secondary">Desde esta pantalla de Historial de Mesas Técnicas puedes consultar los registros históricos asociados a cada Mesa Técnica de Agua.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-hist-voc" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Historial de Voceros</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite mantener un registro histórico de la participación de los voceros dentro de las Mesas Técnicas de Agua.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Vocero:</strong> Permite seleccionar el vocero correspondiente.</li>
                                        <li><strong>Mesa Técnica:</strong> Permite seleccionar la mesa técnica a la que pertenece.</li>
                                        <li><strong>Motivo de Salida (Si aplica):</strong> Permite registrar la razón por la cual el vocero deja de formar parte de la mesa técnica.</li>
                                        <li><strong>Fecha de Inicio:</strong> Fecha de incorporación del vocero.</li>
                                        <li><strong>Fecha de Fin (Opcional):</strong> Fecha de culminación de sus funciones dentro de la mesa técnica.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar un <code>Nuevo Registro</code> en el historial de voceros debes completar los datos solicitados y presionar el botón <code>Guardar Historial</code>.</p>
                                    <p class="text-secondary">Desde esta pantalla de Historial de Voceros puedes visualizar los períodos de participación de cada vocero dentro de las diferentes Mesas Técnicas de Agua.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-proy" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Proyectos</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite registrar y administrar los proyectos asociados a las Mesas Técnicas del Agua.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Título del Proyecto:</strong> Nombre que identifica el proyecto.</li>
                                        <li><strong>Mesa Técnica Responsable:</strong> Permite seleccionar la mesa técnica encargada del proyecto.</li>
                                        <li><strong>Estado:</strong> Permite indicar la situación actual del proyecto.</li>
                                        <li><strong>Fecha de Registro:</strong> Fecha en que se registra el proyecto.</li>
                                        <li><strong>Ubicación / Dirección:</strong> Lugar donde se desarrollará el proyecto.</li>
                                        <li><strong>Descripción del Proyecto:</strong> Permite detallar los objetivos, alcance o actividades relacionadas con el proyecto.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar un <code>Nuevo Proyecto</code> debes completar la información requerida y posteriormente presionar el botón <code>Guardar Proyecto</code>.</p>
                                    <p class="text-secondary">En la gestión de proyectos podrás consultar todos los proyectos registrados dentro del sistema, realizar búsquedas, editar registros existentes o eliminarlos cuando sea necesario.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-inci" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Incidencias</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite registrar y dar seguimiento a las incidencias relacionadas con el servicio de agua.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Título de la Incidencia:</strong> Nombre o descripción breve del problema reportado.</li>
                                        <li><strong>Estado:</strong> Permite indicar la condición actual de la incidencia.</li>
                                        <li><strong>Mesa Técnica Responsable:</strong> Mesa técnica encargada de gestionar la incidencia.</li>
                                        <li><strong>Comunidad Afectada:</strong> Comunidad donde se presenta la situación.</li>
                                        <li><strong>Tipo de Incidencia:</strong> Clasificación de la incidencia registrada.</li>
                                        <li><strong>Prioridad:</strong> Nivel de importancia asignado al caso.</li>
                                        <li><strong>Fecha del Suceso:</strong> Fecha en la que ocurrió la incidencia.</li>
                                        <li><strong>Descripción Detallada:</strong> Permite registrar información detallada sobre el problema reportado.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar una <code>Nueva Incidencia</code> debes completar la información solicitada y presionar el botón <code>Guardar Incidencia</code>.</p>
                                    <p class="text-secondary">En la gestión de incidencias podrás consultar todas las incidencias registradas dentro del sistema, realizar búsquedas, editar registros existentes o eliminarlos cuando sea necesario.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-user" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Usuarios</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite administrar los usuarios que tienen acceso al sistema.</p>
                                    <div class="row g-3 text-secondary mb-3">
                                        <div class="col-md-6">
                                            <strong>Información de Perfil requerida:</strong>
                                            <ul>
                                                <li>Nombre y Apellido completo.</li>
                                                <li>Cédula de Identidad única.</li>
                                                <li>Fecha de Nacimiento.</li>
                                                <li>Dirección de Correo Electrónico institucional.</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Seguridad y Privilegios:</strong>
                                            <ul>
                                                <li><strong>Asignar Rol:</strong> Establece el nivel preciso de acceso de la cuenta al sistema.</li>
                                                <li>Contraseña y Validación de Seguridad.</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="text-secondary">En esta interfaz interactiva podrá realizar búsquedas exhaustivas, dar de alta nuevos operadores, o seleccionar la opción de <code>Editar</code> para actualizar datos sensibles o revocar accesos en tiempo real de forma segura.</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <small class="text-muted mx-auto">Manual de Usuario del Sistema - Hidrosuroeste v1.2</small>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Entendido / Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active'); // Esto activará el CSS para mostrarla
        });

        function toggleDropdown(element) {
            const parent = element.parentElement;
            parent.classList.toggle('open');
        }

        const sidebar = document.querySelector('.sidebar');
        const openBtn = document.getElementById('sidebarToggle'); // El botón que ya creaste en el header
        const closeBtn = document.getElementById('sidebarClose'); // El nuevo botón dentro del sidebar

        // Abrir sidebar
        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        // Cerrar sidebar
        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });

        // Opcional: Cerrar si se hace clic fuera del sidebar
        document.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !openBtn.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>