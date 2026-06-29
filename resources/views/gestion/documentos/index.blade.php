@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="file-text" class="me-2"></i>Gestión de Documentos
        </h4>
        <a href="{{ route('documentos.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
            <i data-lucide="upload-cloud" class="me-2"></i> Subir Documento
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
            <i data-lucide="check-circle" class="me-2" style="width: 18px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="panel-card" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <div class="p-4 border-bottom">
            <form action="{{ route('documentos.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" name="buscar" class="form-control border-start-0 ps-0" placeholder="Buscar por descripción o entidad..." value="{{ $buscar ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100" style="background: var(--secondary); border: none;">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Archivo / Entidad</th>
                        <th class="py-3">Descripción</th>
                        <th class="py-3">Fecha</th>
                        <th class="py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentos as $doc)
                    <tr>
                        <td class="ps-4">#{{ $doc->documento_id }}</td>
                        <td>
                            <div class="fw-bold" style="color: var(--primary);">
                                <a href="{{ asset('storage/' . $doc->url_archivo) }}" target="_blank" class="text-decoration-none">
                                    <i data-lucide="external-link" class="me-1" style="width: 14px;"></i> Ver Archivo
                                </a>
                            </div>
                            <small class="badge bg-light text-dark border">{{ ucfirst($doc->entidad) }}</small>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;">{{ $doc->descripcion ?? 'Sin descripción' }}</div>
                        </td>
                        <td>
                            <small class="text-muted">{{ date('d/m/Y H:i', strtotime($doc->fecha)) }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('documentos.edit', $doc->documento_id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i data-lucide="edit-3" style="width: 16px;"></i>
                                </a>
                                <form action="{{ route('documentos.destroy', $doc->documento_id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este documento?')">
                                        <i data-lucide="trash-2" style="width: 16px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No hay documentos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">Mostrando {{ $documentos->firstItem() ?? 0 }} a {{ $documentos->lastItem() ?? 0 }} de {{ $documentos->total() }} registros</small>
            <div>{{ $documentos->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
</div>
@endsection