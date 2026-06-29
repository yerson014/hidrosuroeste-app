@extends('layouts.app')

@section('content')
<div class="container-fluid no-print">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color: var(--primary);">Reporte Consolidado de Incidencias</h4>
            <small class="text-muted">
                Periodo: {{ $fecha_inicio ? \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') : 'Inicio' }} 
                al {{ $fecha_fin ? \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') : 'Fin' }}
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('incidencias.index') }}" class="btn btn-light border d-flex align-items-center">
                <i data-lucide="arrow-left" class="me-2" style="width: 18px;"></i> Volver
            </a>
            <button onclick="window.print()" class="btn btn-danger d-flex align-items-center shadow-sm">
                <i data-lucide="printer" class="me-2" style="width: 18px;"></i> Imprimir Reporte
            </button>
        </div>
    </div>
</div>

<div class="report-landscape">
    <div class="report-paper shadow-sm">
        <!-- Encabezado -->
        <div class="report-header">
            <div class="row align-items-center">
                <div class="col-7">
                    <h2 class="company-name">HIDROSUROESTE</h2>
                    <p class="subtitle">Gerencia de Desarrollo Comunitario</p>
                    <p class="location">Listado General de Incidencias Técnicas</p>
                </div>
                <div class="col-5 text-end">
                    <div class="badge-report">Documento Administrativo</div>
                    <p class="small text-muted mb-0">Fecha Impresión: {{ date('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Tabla de Contenido -->
        <div class="report-body">
            <table class="table-report">
                <thead>
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 12%">Fecha</th>
                        <th style="width: 25%">Título / Descripción</th>
                        <th style="width: 15%">MTA / Comunidad</th>
                        <th style="width: 12%">Tipo</th>
                        <th style="width: 10%">Prioridad</th>
                        <th style="width: 10%">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidencias as $inc)
                    <tr>
                        <td class="text-center">#{{ $inc->incidencia_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($inc->fecha)->format('d/m/Y') }}</td>
                        <td>
                            <div class="fw-bold">{{ $inc->titulo }}</div>
                            <small class="text-muted text-truncate d-block" style="max-width: 250px;">{{ $inc->descripcion }}</small>
                        </td>
                        <td>
                            <div class="small">{{ $inc->mesaTecnica->nombre ?? 'N/A' }}</div>
                            <div class="text-muted extra-small">{{ $inc->comunidad->nombre ?? 'N/A' }}</div>
                        </td>
                        <td class="text-capitalize">{{ $inc->tipo ?? 'N/A' }}</td>
                        <td class="text-center">
                            <span class="prio-label prio-{{ strtolower($inc->prioridad) }}">
                                {{ strtoupper($inc->prioridad) }}
                            </span>
                        </td>
                        <td class="text-center text-uppercase fw-bold" style="font-size: 0.75rem;">
                            {{ $inc->estado }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No se encontraron registros en el rango seleccionado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Firmas al final -->
        <div class="report-footer-content mt-5">
            <div class="row text-center">
                <div class="col-4">
                    <div class="sig-line"></div>
                    <p class="small mb-0">Revisado por</p>
                    <p class="extra-small text-muted">Gestión Comunitaria</p>
                </div>
                <div class="col-4">
                    <div class="sig-line"></div>
                    <p class="small mb-0">Sello Institucional</p>
                </div>
                <div class="col-4">
                    <div class="sig-line"></div>
                    <p class="small mb-0">Aprobado por</p>
                    <p class="extra-small text-muted">Gerencia Técnica</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .report-landscape {
        padding: 20px;
        display: flex;
        justify-content: center;
        background-color: #f0f2f5;
    }

    .report-paper {
        background: white;
        width: 297mm; /* A4 Landscape */
        min-height: 210mm;
        padding: 1.5cm;
        position: relative;
    }

    .report-header {
        border-bottom: 2px solid #0056b3;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }

    .company-name { color: #0056b3; font-weight: 900; margin: 0; font-size: 1.5rem; }
    .subtitle { text-transform: uppercase; font-weight: 700; font-size: 0.7rem; color: #666; margin: 0; }
    .badge-report { display: inline-block; background: #f8f9fa; border: 1px solid #ddd; padding: 2px 10px; font-size: 0.65rem; font-weight: bold; }

    .table-report {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .table-report th {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 10px;
        text-align: left;
        color: #333;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .table-report td {
        border: 1px solid #dee2e6;
        padding: 8px;
        vertical-align: top;
    }

    .extra-small { font-size: 0.7rem; }
    .prio-label { font-size: 0.65rem; font-weight: bold; padding: 2px 6px; border-radius: 3px; border: 1px solid #ccc; }
    .prio-alta { background: #fff5f5; color: #dc3545; border-color: #feb2b2; }
    .prio-media { background: #fffaf0; color: #dd6b20; border-color: #fbd38d; }
    .prio-baja { background: #f0fff4; color: #38a169; border-color: #9ae6b4; }

    .sig-line { border-top: 1px solid #000; margin: 40px 20px 5px 20px; }

    @media print {
        .no-print, .sidebar, header, nav { display: none !important; }
        body { background: white !important; }
        .report-landscape { padding: 0; background: white; }
        .report-paper { box-shadow: none; width: 100%; padding: 0; }
        @page { size: landscape; margin: 1cm; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection