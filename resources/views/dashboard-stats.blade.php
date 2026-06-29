<style>
    :root {
        --primary-mta: #2563eb;
        --secondary-vocero: #0891b2;
        /* Nuevos colores vivos para las secciones secundarias */
        --vibrant-green: #10b981;
        --vibrant-green-soft: #f0fdf4;
        --vibrant-orange: #f59e0b;
        --vibrant-orange-soft: #fffbeb;
        --vibrant-red: #ef4444;
        --vibrant-red-soft: #fef2f2;
    }

    .dashboard-grid {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        padding: 1rem;
    }

    /* --- EJE CENTRAL (MTA y Voceros) --- */
    .core-process-section {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .core-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 2px solid #e2e8f0;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s;
        position: relative;
        overflow: hidden;
    }

    .core-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-mta);
    }

    .core-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-mta), var(--secondary-vocero));
    }

    .core-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .core-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-mta);
    }

    .core-data h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        color: #1e293b;
    }

    .core-data p {
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        margin: 0;
    }

    /* --- SECCIONES SECUNDARIAS (Territorial y Control) --- */
    .secondary-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .stats-label {
        font-weight: 700;
        color: #475569;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Estilo de Mini Card con Animación de Elevación */
    .mini-card {
        background: #ffffff;
        padding: 1.25rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    /* Aplicamos la animación de elevación que pediste */
    .mini-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    /* --- VARIANTES DE COLOR SOLICITADAS --- */

    /* Verde para Territorial */
    .card-territorial-vibrant {
        border-bottom: 3px solid var(--vibrant-green);
    }
    .card-territorial-vibrant:hover {
        border-color: var(--vibrant-green);
    }
    .icon-territorial-vibrant {
        background: var(--vibrant-green-soft);
        color: var(--vibrant-green);
    }

    /* Naranja para Control y Gestión */
    .card-control-vibrant {
        border-bottom: 3px solid var(--vibrant-orange);
    }
    .card-control-vibrant:hover {
        border-color: var(--vibrant-orange);
    }
    .icon-control-vibrant {
        background: var(--vibrant-orange-soft);
        color: var(--vibrant-orange);
    }

    /* Rojo para Incidencias (Opcional, pero mantiene la viveza) */
    .card-alert-vibrant {
        border-bottom: 3px solid var(--vibrant-red);
    }
    .icon-alert-vibrant {
        background: var(--vibrant-red-soft);
        color: var(--vibrant-red);
    }

    .mini-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mini-info h4 {
        margin: 0;
        font-size: 1.25rem;
        color: #1e293b;
    }

    .mini-info p {
        margin: 0;
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .core-process-section, .secondary-section {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-grid">
    
    <!-- EJE CENTRAL: MESAS TÉCNICAS Y VOCEROS -->
    <div>
        <div class="stats-label"><i data-lucide="activity"></i> Procesos del Sistema</div>
        <div class="core-process-section">
            <div class="core-card">
                <div class="core-header">
                    <div class="core-icon">
                        <i data-lucide="droplets" size="32"></i>
                    </div>
                    <div class="core-data">
                        <p>Mesas Técnicas del Agua</p>
                        <h2>{{ \App\Models\MesaTecnica::count() }}</h2>
                    </div>
                </div>
                <div style="font-size: 0.8rem; color: #10b981; font-weight: 600;">
                    <i data-lucide="trending-up" size="12"></i> Registro activo y verificado
                </div>
            </div>

            <div class="core-card">
                <div class="core-header">
                    <div class="core-icon" style="background: rgba(8, 145, 178, 0.1); color: var(--secondary-vocero);">
                        <i data-lucide="users" size="32"></i>
                    </div>
                    <div class="core-data">
                        <p>Voceros Registrados</p>
                        <h2>{{ \App\Models\Vocero::count() }}</h2>
                    </div>
                </div>
                <div style="font-size: 0.8rem; color: #10b981; font-weight: 600;">
                    <i data-lucide="check-circle" size="12"></i> Voceros vinculados a MTA
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN TERRITORIAL (Ahora en Verde Vivo con Animación) -->
    <div>
        <div class="stats-label"><i data-lucide="map"></i> Distribución Territorial</div>
        <div class="secondary-section">
            <div class="mini-card card-territorial-vibrant">
                <div class="mini-icon icon-territorial-vibrant"><i data-lucide="map-pin"></i></div>
                <div class="mini-info">
                    <h4>{{ \App\Models\Municipio::count() }}</h4>
                    <p>Municipios</p>
                </div>
            </div>
            <div class="mini-card card-territorial-vibrant">
                <div class="mini-icon icon-territorial-vibrant"><i data-lucide="layers"></i></div>
                <div class="mini-info">
                    <h4>{{ \App\Models\Parroquia::count() }}</h4>
                    <p>Parroquias</p>
                </div>
            </div>
            <div class="mini-card card-territorial-vibrant">
                <div class="mini-icon icon-territorial-vibrant"><i data-lucide="home"></i></div>
                <div class="mini-info">
                    <h4>{{ \App\Models\Comunidad::count() }}</h4>
                    <p>Comunidades</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN CONTROL Y SEGUIMIENTO (Naranja con Animación) -->
    <div>
        <div class="stats-label"><i data-lucide="clipboard-list"></i> Control y Gestión de Resultados</div>
        <div class="secondary-section" style="grid-template-columns: repeat(2, 1fr);">
            <div class="mini-card card-control-vibrant">
                <div class="mini-icon icon-control-vibrant"><i data-lucide="file-spreadsheet"></i></div>
                <div class="mini-info">
                    <h4>{{ \App\Models\Proyecto::count() }}</h4>
                    <p>Proyectos Comunitarios</p>
                </div>
            </div>
            <div class="mini-card card-alert-vibrant">
                <div class="mini-icon icon-alert-vibrant"><i data-lucide="alert-triangle"></i></div>
                <div class="mini-info">
                    <h4>{{ \App\Models\Incidencia::count() }}</h4>
                    <p>Incidencias de Servicio</p>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>