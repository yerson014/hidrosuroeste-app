@extends('layouts.app')

@section('content')
<div class="container-fluid no-print">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color: var(--primary);">Ficha de Registro de MTA</h4>
            <small class="text-muted">Documento oficial de la organización social.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('mesas-tecnicas.index') }}" class="btn btn-light border d-flex align-items-center">
                <i data-lucide="arrow-left" class="me-2" style="width: 18px;"></i> Volver al Listado
            </a>
            <button onclick="window.print()" class="btn btn-danger d-flex align-items-center shadow-sm">
                <i data-lucide="printer" class="me-2" style="width: 18px;"></i> Imprimir / Guardar PDF
            </button>
        </div>
    </div>
</div>

<div class="report-container">
    <div class="report-paper shadow-sm">
        <div class="report-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h2 class="company-name">HIDROSUROESTE</h2>
                    <p class="subtitle">Gerencia de Desarrollo Comunitario</p>
                    <p class="location">Estado Táchira, Venezuela</p>
                </div>
                <div class="col-4 text-end">
                    <div class="badge-report">Registro de Organización</div>
                    <h5 class="report-id">MTA-{{ str_pad($mesa->mesa_tecnica_id, 5, '0', STR_PAD_LEFT) }}</h5>
                </div>
            </div>
        </div>

        <div class="report-body">
            <div class="report-section">
                <h6 class="section-title">Información de la Mesa Técnica</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="info-label">Nombre de la Organización</label>
                        <div class="info-value">{{ $mesa->nombre }}</div>
                    </div>
                    <div class="col-6">
                        <label class="info-label">Estatus de Operatividad</label>
                        <div class="info-value">
                            @if($mesa->estado == 'activa')
                                <span class="text-success fw-bold">ACTIVA / VIGENTE</span>
                            @else
                                <span class="text-secondary fw-bold">INACTIVA</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="info-label">Fecha de Registro</label>
                        <div class="info-value">{{ $mesa->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <h6 class="section-title">Vinculación Territorial y Social</h6>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="info-label">Consejo Comunal</label>
                        <div class="info-value">{{ $mesa->consejoComunal->nombre ?? 'Sin Consejo Comunal asignado' }}</div>
                    </div>
                    <div class="col-6">
                        <label class="info-label">Centro de Distribución</label>
                        <div class="info-value">{{ $mesa->centroAsociado->nombre ?? 'N/A' }}</div>
                    </div>
                    <div class="col-12">
                        <label class="info-label">Dirección / Punto de Referencia</label>
                        <div class="info-value">{{ $mesa->direccion ?? 'No especificada' }}</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <h6 class="section-title">Observaciones Técnicas</h6>
                <div class="description-text">
                    {{ $mesa->observaciones ?? 'No se registraron observaciones adicionales para esta Mesa Técnica del Agua.' }}
                </div>
            </div>

            <div class="report-section mb-0">
                <div class="status-footer d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small">Registrado por:</span>
                        <span class="ms-2 fw-bold">{{ Auth::user()->usuario ?? 'Sistema' }}</span>
                    </div>
                    <div>
                        <span class="text-muted small">Fecha de Impresión:</span>
                        <span class="ms-2 fw-bold">{{ date('d/m/Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="signature-grid">
                <div class="sig-box">
                    <div class="sig-line">Vocero(a) de la MTA</div>
                    <div class="sig-sub">Representante Comunal</div>
                </div>
                <div class="sig-box">
                    <div class="sig-line">Gerencia Comunitaria</div>
                    <div class="sig-sub">Sello y Firma Hidrosuroeste</div>
                </div>
            </div>
        </div>

        <div class="report-footer">
            Este documento es una copia fiel del registro digital en el sistema de Gestión Comunitaria de Hidrosuroeste.
        </div>
    </div>
</div>

<style>
    .report-container {
        padding: 20px;
        display: flex;
        justify-content: center;
        background-color: #f0f2f5;
    }

    .report-paper {
        background: white;
        width: 210mm;
        min-height: 297mm;
        padding: 2.5cm;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        position: relative;
    }

    .report-header {
        border-bottom: 2px solid #0056b3;
        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .company-name {
        color: #0056b3;
        font-weight: 900;
        margin: 0;
        font-size: 1.8rem;
    }

    .subtitle {
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        font-size: 0.8rem;
        margin: 0;
        color: #666;
    }

    .badge-report {
        display: inline-block;
        background: #f8f9fa;
        border: 1px solid #ddd;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: bold;
        text-transform: uppercase;
    }

    .section-title {
        background: #f8f9fa;
        padding: 8px 15px;
        color: #0056b3;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.8rem;
        border-left: 4px solid #0056b3;
        margin-bottom: 15px;
    }

    .report-section {
        margin-bottom: 35px;
    }

    .info-label {
        display: block;
        font-size: 0.7rem;
        font-weight: bold;
        color: #888;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
    }

    .description-text {
        padding: 15px;
        background: #fafafa;
        border: 1px dashed #ccc;
        border-radius: 4px;
        line-height: 1.6;
        font-size: 0.9rem;
        text-align: justify;
    }

    /* CAMBIO: Cuadrícula de firmas centrada */
    .signature-grid {
        margin-top: 80px;
        display: grid;
        grid-template-columns: repeat(2, 250px); /* Dos columnas de tamaño fijo */
        justify-content: center; /* Centra las columnas en el papel */
        gap: 100px; /* Espacio entre firmas */
        text-align: center;
    }

    .sig-line {
        border-top: 1px solid #333;
        padding-top: 8px;
        font-weight: bold;
        font-size: 0.75rem;
    }

    .sig-sub { font-size: 0.65rem; color: #666; }

    .report-footer {
        position: absolute;
        bottom: 30px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 0.65rem;
        color: #aaa;
    }

    @media print {
        .no-print, .sidebar, header, nav { display: none !important; }
        body { background: white !important; margin: 0; padding: 0; }
        .report-container { padding: 0; background: white; }
        .report-paper { box-shadow: none; width: 100%; padding: 0; }
        .main-content { margin: 0 !important; padding: 0 !important; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection