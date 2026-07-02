@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="alert-circle" class="me-2"></i>Gestión de Incidencias
        </h4>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalReporteFechas" style="border-radius: 8px; padding: 10px 20px;">
                <i data-lucide="file-text" class="me-2"></i> Reporte General
            </button>

            <a href="{{ route('incidencias.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
                <i data-lucide="plus" class="me-2"></i> Nueva Incidencia
            </a>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    <div class="panel-card" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <div class="p-4 border-bottom">
            <form action="{{ route('incidencias.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" 
                                   name="buscar" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar por título, tipo, prioridad o estado..." 
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
                        <a href="{{ route('incidencias.index') }}" class="btn btn-light w-100">
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
                        <th class="py-3">Título / Tipo</th>
                        <th class="py-3">Mesa / Comunidad</th>
                        <th class="py-3">Prioridad</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3">Fecha del Suceso</th>
                        <th class="py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidencias as $incidencia)
                    <tr>
                        <td class="ps-4">#{{ $incidencia->incidencia_id }}</td>
                        <td>
                            <div class="fw-bold" style="color: var(--primary);">{{ $incidencia->titulo }}</div>
                            <small class="text-muted">
                                <i data-lucide="tag" style="width: 10px;"></i> {{ $incidencia->tipo ?? 'Sin tipo' }}
                            </small>
                        </td>
                        <td>
                            <div class="small fw-semibold">{{ $incidencia->mesaTecnica->nombre ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $incidencia->comunidad->nombre ?? 'N/A' }}</small>
                        </td>
                        <td>
                            @php
                                $prioClass = match(strtolower($incidencia->prioridad)) {
                                    'alta' => 'text-danger',
                                    'media' => 'text-warning',
                                    'baja' => 'text-success',
                                    default => 'text-muted'
                                };
                            @endphp
                            <span class="fw-bold {{ $prioClass }}">{{ ucfirst($incidencia->prioridad ?? 'N/A') }}</span>
                        </td>
                        <td>
                            @if($incidencia->estado == 'registrada')
                                <span class="badge bg-info text-white">Registrada</span>
                            @elseif($incidencia->estado == 'atendida')
                                <span class="badge bg-warning text-dark">Atendida</span>
                            @else
                                <span class="badge bg-success text-white">Resuelta</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">
                                <i data-lucide="calendar" class="me-1" style="width: 14px;"></i>
                                {{ $incidencia->fecha ? \Carbon\Carbon::parse($incidencia->fecha)->format('d/m/Y') : 'N/A' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('incidencias.show', $incidencia->incidencia_id) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Ver Reporte PDF">
                                    <i data-lucide="file-text" style="width: 16px;"></i>
                                </a>

                                <a href="{{ route('incidencias.edit', $incidencia->incidencia_id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i data-lucide="edit-3" style="width: 16px;"></i>
                                </a>
                                <form action="{{ route('incidencias.destroy', $incidencia->incidencia_id) }}" method="POST" class="form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-swal-delete" title="Eliminar">
                                        <i data-lucide="trash-2" style="width: 16px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No hay complicaciones registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando {{ $incidencias->firstItem() ?? 0 }} a {{ $incidencias->lastItem() ?? 0 }} de {{ $incidencias->total() }} registros
            </small>
            <div>
                {{ $incidencias->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReporteFechas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">Generar Reporte General</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('incidencias.reporte_general') }}" method="GET" target="_blank">
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Seleccione el rango de fechas para filtrar los registros en el reporte.</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-uppercase">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-uppercase">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4">Generar Reporte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const deleteButtons = document.querySelectorAll('.btn-swal-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.form-eliminar');
                
                Swal.fire({
                    title: '¿Eliminar incidencia?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection