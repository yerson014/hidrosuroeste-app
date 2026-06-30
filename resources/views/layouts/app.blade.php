<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hidrosuroeste - Gestión Comunitaria</title>
    
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
                <h5 class="m-0 fw-bold text-muted" style="font-size: 1rem;">Panel de Administración - Gerencia Comunitaria</h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-warning d-flex align-items-center fw-bold btn-sm px-3 shadow-sm text-dark" data-bs-toggle="modal" data-bs-target="#manualUsuarioModal" style="border-radius: 8px;">
                    <i data-lucide="help-circle" class="me-2" style="width: 18px; height: 18px;"></i> Manual de Ayuda
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
                                    <p class="text-secondary leading-relaxed">Al acceder satisfactoriamente al sistema, visualizará el Dashboard o panel central de estadísticas. Desde este módulo podrá consultar de forma rápida, dinámica y en tiempo real la información general relacionada con las <strong>Mesas Técnicas de Agua (MTA)</strong>, proyectos e incidencias operativas activas, además de navegar de forma directa a los submódulos usando el menú lateral o la barra de procesos rápidos.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-muni" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Gestión de Municipios</h4>
                                    <p class="text-secondary leading-relaxed">Este módulo permite estructurar la división político-territorial base de las comunidades asignadas a Hidrosuroeste.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Registrar Municipio:</strong> Permite ingresar y seleccionar la entidad que desea registrar. Para concretar, asigne el nombre correspondiente de la lista del Estado Táchira y presione el botón <code>Guardar Municipio</code>.</li>
                                        <li><strong>Listado de Municipios:</strong> Desde esta pantalla de control puede auditar todos los municipios vigentes, realizar búsquedas rápidas mediante filtros, editar los nombres existentes o eliminar registros de manera permanente.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-parr" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Parroquias</h4>
                                    <p class="text-secondary leading-relaxed">Este submódulo permite administrar y asociar las parroquias correspondientes a cada municipio registrado.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Municipio perteneciente:</strong> Menú desplegable para seleccionar la entidad territorial de origen que condicionará la parroquia.</li>
                                        <li><strong>Nombre de la Parroquia:</strong> Identificador único del sector dentro del sistema.</li>
                                    </ul>
                                    <p class="text-secondary">Para registrar una nueva entidad, asigne la relación correcta y valide en el botón de guardado. Podrá actualizar o eliminar registros desde la tabla de consulta general.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-comu" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Comunidades</h4>
                                    <p class="text-secondary leading-relaxed">Permite el registro, caracterización sociodemográfica y diagnóstico técnico de las redes de los sectores habitacionales.</p>
                                    <h6 class="fw-bold text-dark mt-2">Campos Técnicos y Demográficos:</h6>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Ubicación:</strong> Selección de la Parroquia perteneciente, Nombre de la Comunidad y Sector/Zona específica.</li>
                                        <li><strong>Demografía:</strong> Registro cuantitativo de Población Total, Familias, Hombres, Mujeres y Niños.</li>
                                        <li><strong>Estatus del Servicio (Toggles):</strong> Permite activar si la comunidad <i>Usa Cisterna</i>, si cuenta con red de <i>Agua Potable</i>, si está catalogada en <i>Zonas de Silencio</i> (sin suministro continuo) o si posee <i>Tanques Grandes</i>.</li>
                                    </ul>
                                    <p class="text-secondary">Utilice <code>Guardar Comunidad</code> para registrar o <code>Editar</code> desde la grilla principal para modificar parámetros de población.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-comunas" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Comunas</h4>
                                    <p class="text-secondary leading-relaxed">Permite administrar las estructuras de agregación e integración comunal dentro del estado.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Formulario de Registro:</strong> Requiere seleccionar de forma obligatoria la Parroquia, enlazar opcionalmente una Comunidad y escribir la denominación legal en el campo <i>Nombre de la Comuna</i>.</li>
                                        <li><strong>Modificación:</strong> Presione <code>Guardar Comuna</code> para almacenar, o use el botón de edición para ajustar el nombre o añadir comunidades socias.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-centros" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Centros Educativos e Institucionales</h4>
                                    <p class="text-secondary leading-relaxed">Mapea la infraestructura prioritaria (CDI, Escuelas, UBCH) vinculada a los territorios para optimizar planes de contingencia hídrica.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Formulario:</strong> Vincule la Comunidad perteneciente, categorice el espacio en <i>Tipo de Centro</i>, registre el nombre oficial del establecimiento y la <i>Dirección o Ubicación Técnica</i> exacta.</li>
                                        <li><strong>Acción:</strong> Presione <code>Guardar Registro</code> para añadir y audite el listado general ante cambios institucionales.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-consejos" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Consejos Comunales</h4>
                                    <p class="text-secondary leading-relaxed">Controla las organizaciones comunitarias de base que dan origen y legitimidad a los comités técnicos del agua.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Datos requeridos:</strong> Selección de la Comunidad perteneciente y el Nombre oficial del Consejo Comunal.</li>
                                        <li><strong>Enlace de Coordinación:</strong> Datos del vocero líder como el <i>Nombre del Líder</i> y su <i>Teléfono de Contacto</i> directo.</li>
                                    </ul>
                                    <p class="text-secondary">Esencial para actualizar números telefónicos y renovaciones de vocerías mediante la opción <code>Editar</code>.</p>
                                </div>

                                <div class="tab-pane fade" id="v-pills-mta" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Mesas Técnicas de Agua (MTA)</h4>
                                    <div style="display: block; width: 100%;">
                                        <p class="text-secondary leading-relaxed">Eje fundamental del sistema operativo. Permite gestionar, registrar y auditar las Mesas Técnicas encargadas de la supervisión de las redes hidráulicas comunitarias.</p>
                                        <p class="text-secondary">Cada registro requiere la vinculación obligatoria a una comunidad o consejo comunal legalmente constituido, la declaración del número total de integrantes activos y el estatus actual de funcionamiento de la misma.</p>
                                        <div class="alert alert-warning d-flex align-items-center mt-3">
                                            <i data-lucide="alert-circle" class="me-2"></i> <strong>Regla de Validación:</strong> El sistema restringe la estructura a un máximo estricto de 12 integrantes (6 voceros principales y 6 voceros suplentes). No se admiten nombres duplicados.
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="v-pills-voceros" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Módulo de Gestión de Voceros</h4>
                                    <p class="text-secondary leading-relaxed">Administra el padrón y las fichas de identidad de los ciudadanos que integran de forma activa las MTA registradas.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Información obligatoria:</strong> Cédula de Identidad (ID único de validación), Nombres, Apellidos, Género, Teléfono, Estado (Activo/Inactivo) y Dirección de Habitación.</li>
                                        <li><strong>Búsqueda:</strong> El panel principal permite filtrar inmediatamente por cédula, nombre o teléfono para agilizar las auditorías de los comités.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-hist-mta" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Historial de Mesas Técnicas</h4>
                                    <p class="text-secondary leading-relaxed">Asegura la memoria institucional y trazabilidad del trabajo de campo comunitario.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Registro de Eventos:</strong> Permite seleccionar una Mesa Técnica y documentar mediante una <i>Descripción del Evento</i> las minutas de asambleas, inspecciones de ingenieros de Hidrosuroeste o jornadas de mantenimiento.</li>
                                        <li>Asigne la <i>Fecha y Hora del Evento</i> exacta y guarde con el botón <code>Guardar en Historial</code>.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-hist-voc" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Historial de Gestión de Voceros</h4>
                                    <p class="text-secondary leading-relaxed">Audita los tiempos de permanencia, incorporaciones y rotaciones del poder popular dentro de las mesas de agua.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Campos del Histórico:</strong> Selección del Vocero, vinculación a la Mesa Técnica correspondiente y fijación de la <i>Fecha de Inicio</i> y <i>Fecha de Fin</i>.</li>
                                        <li><strong>Validación de Cese:</strong> Al desvincular un vocero, el sistema exige rellenar el campo <i>Motivo de Salida</i> (por ejemplo: mudanza, renovación de vocerías) para mantener limpia la auditoría de datos.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-proy" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Control de Proyectos Comunitarios</h4>
                                    <p class="text-secondary leading-relaxed">Monitorea de forma centralizada el ciclo de vida de las propuestas de infraestructura hídrica introducidas por el poder popular.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Campos de Ficha:</strong> Título del Proyecto, Mesa Técnica Responsable, Ubicación de la Obra, Fecha de Carga y una amplia Descripción Detallada del alcance técnico.</li>
                                        <li><strong>Flujo de Estatus:</strong> Permite actualizar el estado del proyecto según las inspecciones a: <i>En Revisión, Aprobado, En Ejecución o Finalizado</i>.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-inci" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Gestión de Incidencias de Servicio</h4>
                                    <p class="text-secondary leading-relaxed">Canaliza reportes de fallas de borde, fracturas de tuberías principales o solicitudes críticas de abastecimiento.</p>
                                    <ul class="text-secondary mb-3">
                                        <li><strong>Apertura de Reportes:</strong> Defina un Título descriptivo, Tipo de Incidencia, Comunidad Afectada y la Mesa Técnica que reporta.</li>
                                        <li><strong>Priorización Técnica:</strong> Clasifique la urgencia mediante el combo de <i>Prioridad</i> (Baja, Media, Alta, Crítica) y añada la descripción del daño para el despliegue inmediato de las cuadrillas de ingenieros.</li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="v-pills-user" role="tabpanel">
                                    <h4 class="fw-bold mb-3 text-primary">Gestión de Usuarios e Identidad</h4>
                                    <p class="text-secondary leading-relaxed">Permite al administrador auditar el control de accesos, roles y perfiles del personal dentro de la plataforma.</p>
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