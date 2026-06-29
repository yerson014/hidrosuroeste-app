@extends('layouts.app')

@section('content')
<style>
    /* Estilos base del reporte */
    .report-container {
        background-color: white;
        padding: 50px;
        margin: 20px auto;
        max-width: 1000px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        border: 1px solid #eef2f7;
    }

    /* Estilo del título principal fuera del folio */
    .page-title-section {
        max-width: 1000px;
        margin: 0 auto;
    }

    .report-header {
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .header-logo-text {
        font-weight: 800;
        color: #0d47a1;
        font-size: 26px;
        letter-spacing: 1px;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* Tablas de información */
    .info-table {
        width: 100%;
        margin-bottom: 25px;
        border-collapse: collapse;
    }

    .info-table th {
        background-color: #f8faff;
        border: 1px solid #dee2e6;
        padding: 12px;
        width: 25%;
        text-align: left;
        color: #495057;
        font-size: 0.9rem;
    }

    .info-table td {
        border: 1px solid #dee2e6;
        padding: 12px;
        color: #212529;
        font-size: 0.95rem;
    }

    .description-box {
        border: 1px solid #dee2e6;
        padding: 25px;
        background-color: #ffffff;
        min-height: 180px;
        margin-top: 10px;
        line-height: 1.6;
    }

    .watermark-text {
        font-size: 11px;
        color: #adb5bd;
        text-transform: uppercase;
    }

    @media print {
        .no-print { display: none !important; }
        .report-container { box-shadow: none; border: none; margin: 0; max-width: 100%; padding: 20px; }
        body { background-color: white; }
    }
</style>

<div class="container pb-5">
    <div class="page-title-section d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h1 class="h3 fw-bold text-primary mb-1">Ficha de Registro de Incidencia</h1>
            <p class="text-muted mb-0 small">Documento técnico de la organización social.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('incidencias.index') }}" class="btn btn-white border shadow-sm px-4">
                <i class="fas fa-arrow-left me-2"></i> Volver al Listado
            </a>
            <button onclick="window.print()" class="btn btn-danger shadow-sm px-4">
                <i class="fas fa-print me-2"></i> Imprimir / Guardar PDF
            </button>
        </div>
    </div>

    <div class="report-container">
        <div class="report-header d-flex justify-content-between align-items-start">
            <div>
                <div class="header-logo-text">HIDROSUROESTE</div>
                <div class="text-muted fw-bold small">GERENCIA DE DESARROLLO COMUNITARIO</div>
                <div class="mt-2 fw-bold text-dark">Ficha Técnica de Incidencia</div>
            </div>
            <div class="text-end">
                <div class="badge bg-light text-dark border px-3 py-2 mb-2 watermark-text">Documento Administrativo</div>
                <div class="small text-muted">Fecha Impresión: {{ date('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h2 class="h5 fw-bold mb-0">ID Reporte: <span class="text-primary">#{{ str_pad($incidencia->incidencia_id, 5, '0', STR_PAD_LEFT) }}</span></h2>
            </div>
            <div class="col-6 text-end">
                <span class="status-badge {{ $incidencia->estado == 'resuelta' ? 'bg-success text-white' : ($incidencia->estado == 'atendida' ? 'bg-warning text-dark' : 'bg-info text-white') }}">
                    ESTADO: {{ strtoupper($incidencia->estado) }}
                </span>
            </div>
        </div>

        <h6 class="text-primary fw-bold text-uppercase mb-3" style="letter-spacing: 1px;">
            <i class="fas fa-file-alt me-2"></i> Detalles de la Incidencia
        </h6>
        <table class="info-table">
            <tr>
                <th>Título / Asunto</th>
                <td colspan="3" class="fw-bold text-uppercase">{{ $incidencia->titulo }}</td>
            </tr>
            <tr>
                <th>Tipo</th>
                <td>{{ $incidencia->tipo ?? 'N/A' }}</td>
                <th>Prioridad</th>
                <td>
                    <span class="badge {{ $incidencia->prioridad == 'Alta' ? 'bg-danger' : ($incidencia->prioridad == 'Media' ? 'bg-warning text-dark' : 'bg-success') }}">
                        {{ strtoupper($incidencia->prioridad) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Fecha del Suceso</th>
                <td>{{ \Carbon\Carbon::parse($incidencia->fecha)->format('d/m/Y') }}</td>
                <th>Registrado por</th>
                <td>ID Usuario: {{ $incidencia->usuario_id }}</td>
            </tr>
        </table>

        <h6 class="text-primary fw-bold text-uppercase mb-3 mt-4" style="letter-spacing: 1px;">
            <i class="fas fa-map-marker-alt me-2"></i> Ubicación y Mesa Técnica
        </h6>
        <table class="info-table">
            <tr>
                <th>Mesa Técnica (MTA)</th>
                <td>{{ $incidencia->mesaTecnica->nombre }}</td>
            </tr>
            <tr>
                <th>Comunidad Afectada</th>
                <td>{{ $incidencia->comunidad->nombre }}</td>
            </tr>
        </table>

        <h6 class="text-primary fw-bold text-uppercase mb-3 mt-4" style="letter-spacing: 1px;">
            <i class="fas fa-align-left me-2"></i> Descripción del Problema
        </h6>
        <div class="description-box">
            {!! nl2br(e($incidencia->descripcion ?? 'No se proporcionó una descripción detallada para esta incidencia.')) !!}
        </div>

        <div class="mt-5 pt-5">
            <div class="row text-center">
                <div class="col-4">
                    <div style="border-top: 1px solid #dee2e6; width: 80%; margin: 0 auto; padding-top: 10px;">
                        <small class="fw-bold text-muted">Firma Responsable Mesa</small>
                    </div>
                </div>
                <div class="col-4">
                    <div style="border-top: 1px solid #dee2e6; width: 80%; margin: 0 auto; padding-top: 10px;">
                        <small class="fw-bold text-muted">Sello Comunitario</small>
                    </div>
                </div>
                <div class="col-4">
                    <div style="border-top: 1px solid #dee2e6; width: 80%; margin: 0 auto; padding-top: 10px;">
                        <small class="fw-bold text-muted">Recepción Hidrosuroeste</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-5 text-center no-print">
            <small class="text-muted">Este documento es una representación digital del registro de incidencia almacenado en el sistema.</small>
        </div>
    </div>
</div>
@endsection