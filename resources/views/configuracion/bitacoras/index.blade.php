@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="clipboard-list" class="me-2"></i>Bitácora de Actividades
        </h4>
        <a href="{{ route('bitacoras.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
            <i data-lucide="plus" class="me-2"></i> Nuevo Registro
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
            <i data-lucide="check-circle" class="me-2" style="width: 18px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="panel-card" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <!-- Barra de Búsqueda -->
        <div class="p-4 border-bottom">
            <form action="{{ route('bitacoras.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" 
                                   name="buscar" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar por acción, entidad o descripción..." 
                                   value="{{ $buscar ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100" style="background: var(--secondary); border: none;">
                            Filtrar
                        </button>
                    </div>
                    @if($buscar)
                    <div class="col-md-2">
                        <a href="{{ route('bitacoras.index') }}" class="btn btn-light w-100">
                            Limpiar
                        </a>
                    </div>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Fecha / IP</th>
                        <th class="py-3">Usuario</th>
                        <th class="py-3">Acción / Entidad</th>
                        <th class="py-3">Descripción</th>
                        <th class="py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bitacoras as $item)
                    <tr>
                        <td class="ps-4">#{{ $item->bitacora_id }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y H:i') }}</div>
                            <small class="text-muted"><i data-lucide="monitor" class="me-1" style="width: 12px;"></i>{{ $item->ip ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <div class="small fw-semibold">{{ $item->usuario->name ?? 'Sistema' }}</div>
                        </td>
                        <td>
                            @php
                                $badgeColor = match(strtolower($item->accion)) {
                                    'crear' => 'bg-success',
                                    'modificar' => 'bg-warning text-dark',
                                    'eliminar' => 'bg-danger',
                                    'login' => 'bg-info',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }}">{{ ucfirst($item->accion) }}</span>
                            <div class="small text-muted mt-1">{{ $item->entidad }}</div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 250px;" title="{{ $item->descripcion }}">
                                {{ $item->descripcion }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('bitacoras.edit', $item->bitacora_id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i data-lucide="edit-3" style="width: 16px;"></i>
                                </a>
                                <form action="{{ route('bitacoras.destroy', $item->bitacora_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este registro de bitácora?')" title="Eliminar">
                                        <i data-lucide="trash-2" style="width: 16px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No se encontraron registros de actividad.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando {{ $bitacoras->firstItem() ?? 0 }} a {{ $bitacoras->lastItem() ?? 0 }} de {{ $bitacoras->total() }} registros
            </small>
            <div>
                {{ $bitacoras->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endsection