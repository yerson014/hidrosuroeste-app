@extends('layouts.app')

@section('content')
<style>
    .dashboard-title {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 25px;
    }

    .welcome-banner {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 15px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .welcome-banner h1 {
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 2;
    }

    .welcome-banner p {
        opacity: 0.9;
        max-width: 600px;
        position: relative;
        z-index: 2;
    }

    .welcome-banner::after {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .hover-link {
        transition: all 0.3s ease;
    }

    .hover-link:hover {
        background: white !important;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
    }

    @media (max-width: 768px) {
        /* Fuerza a las secciones a ser de una sola columna */
        .core-process-section, 
        .secondary-section {
            grid-template-columns: 1fr !important; /* Una sola columna */
            gap: 1rem !important;
        }

        /* Reducimos el tamaño del banner para que quepa en la pantalla */
        .welcome-banner {
            padding: 20px !important;
        }
        
        .welcome-banner h1 {
            font-size: 1.25rem !important;
        }
    }
</style>

<div class="container-fluid">
    <div class="welcome-banner">
        <h1>¡Bienvenido, {{ Auth::user()->nombre ?? 'Usuario' }}!</h1>
        <p>Panel de Gerencia Comunitaria. Control centralizado de Mesas Técnicas de Agua (MTA) y Vocería Comunal para el desarrollo territorial.</p>
    </div>

    <div class="content-body mb-5">
         @include('dashboard-stats')
    </div> 

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0" style="color: var(--primary);">
                        <i data-lucide="droplet" class="me-2 text-primary" style="width: 20px;"></i>Últimas MTA Registradas
                    </h5>
                    <a href="{{ route('mesas-tecnicas.index') }}" class="btn btn-sm btn-outline-primary border-0 fw-bold">Ver todas</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr class="small text-uppercase text-muted" style="font-size: 0.75rem;">
                                <th>Nombre de la Mesa</th>
                                <th>Municipio / Parroquia</th>
                                <th>Integrantes</th>
                                <th>Estatus</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @php
                                // Obtenemos las últimas 6 MTA con sus relaciones territoriales
                                $ultimasMesas = \App\Models\MesaTecnica::with([
                                    'consejoComunal.comunidad.parroquia.municipio'
                                ])->latest()->take(6)->get();
                            @endphp

                            @forelse($ultimasMesas as $mesa)
                            <tr>
                                <td class="fw-bold" style="color: var(--text-dark);">{{ $mesa->nombre }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $mesa->consejoComunal->comunidad->parroquia->municipio->nombre ?? 'N/A' }}</span>
                                        <small class="text-muted">{{ $mesa->consejoComunal->comunidad->parroquia->nombre ?? 'Sin Parroquia' }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        {{ $mesa->numero_integrantes ?? 0 }} <i data-lucide="users" style="width: 10px;"></i>
                                    </span>
                                </td>
                                <td>
                                    @if($mesa->estado == 'activa')
                                        <span class="badge bg-success-soft text-success border border-success" style="background: #ecfdf5;">Activa</span>
                                    @else
                                        <span class="badge bg-danger-soft text-danger border border-danger" style="background: #fef2f2;">Inactiva</span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ $mesa->fecha_creacion ? \Carbon\Carbon::parse($mesa->fecha_creacion)->format('d/m/Y') : 'S/F' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i data-lucide="database" class="d-block mx-auto mb-2" style="width: 40px; opacity: 0.3;"></i>
                                    No hay Mesas Técnicas registradas actualmente.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4" style="color: var(--primary);">Procesos del Sistema</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('mesas-tecnicas.create') }}" class="btn btn-light text-start p-3 d-flex align-items-center justify-content-between border-0 shadow-sm hover-link" style="background: #f0fdf4;">
                        <span><i data-lucide="droplet" class="me-2 text-success"></i> Registrar Nueva MTA</span>
                        <i data-lucide="plus" style="width: 16px;"></i>
                    </a>
                    <a href="{{ route('voceros.create') }}" class="btn btn-light text-start p-3 d-flex align-items-center justify-content-between border-0 shadow-sm hover-link" style="background: #ecfdf5;">
                        <span><i data-lucide="user-plus" class="me-2 text-info"></i> Asignar Vocero</span>
                        <i data-lucide="plus" style="width: 16px;"></i>
                    </a>
                    <hr class="my-2 opacity-10">
                    <a href="{{ route('municipios.create') }}" class="btn btn-light text-start p-3 d-flex align-items-center justify-content-between border-0 shadow-sm hover-link">
                        <span><i data-lucide="map-pin" class="me-2 text-primary"></i> Estructura Territorial</span>
                        <i data-lucide="chevron-right" style="width: 16px;"></i>
                    </a>
                    <a href="{{ route('incidencias.create') }}" class="btn btn-light text-start p-3 d-flex align-items-center justify-content-between border-0 shadow-sm hover-link">
                        <span><i data-lucide="alert-triangle" class="me-2 text-danger"></i> Reportar Incidencia</span>
                        <i data-lucide="chevron-right" style="width: 16px;"></i>
                    </a>
                </div>

                <div class="mt-4 p-3 bg-light rounded-3 border">
                    <small class="text-muted d-block mb-1">Nota del Proceso:</small>
                    <p class="small m-0 text-dark">Recuerde que cada <b>Vocero</b> debe estar vinculado a una <b>MTA</b> activa para su validez legal.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection