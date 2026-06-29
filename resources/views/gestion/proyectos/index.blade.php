@extends('layouts.app')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="briefcase" class="me-2"></i>Gestión de Proyectos
        </h4>
        <a href="{{ route('proyectos.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
            <i data-lucide="plus" class="me-2"></i> Nuevo Proyecto
        </a>
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
        <!-- Barra de Búsqueda -->
        <div class="p-4 border-bottom">
            <form action="{{ route('proyectos.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" 
                                   name="buscar" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar por título, ubicación o estado..." 
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
                        <a href="{{ route('proyectos.index') }}" class="btn btn-light w-100">
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
                        <th class="py-3">Título / Ubicación</th>
                        <th class="py-3">Mesa Técnica</th>
                        <th class="py-3">Fecha</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyectos as $proyecto)
                    <tr>
                        <td class="ps-4">#{{ $proyecto->proyecto_id }}</td>
                        <td>
                            <div class="fw-bold" style="color: var(--primary);">{{ $proyecto->titulo }}</div>
                            <small class="text-muted">
                                <i data-lucide="map-pin" style="width: 10px;"></i> {{ Str::limit($proyecto->ubicacion ?? 'Sin ubicación', 30) }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $proyecto->mesaTecnica->nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $proyecto->fecha ? \Carbon\Carbon::parse($proyecto->fecha)->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            @if($proyecto->estado == 'propuesto')
                                <span class="badge bg-info text-white">Propuesto</span>
                            @elseif($proyecto->estado == 'aprobado')
                                <span class="badge bg-primary text-white">Aprobado</span>
                            @else
                                <span class="badge bg-success text-white">Ejecutado</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('proyectos.edit', $proyecto->proyecto_id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i data-lucide="edit-3" style="width: 16px;"></i>
                                </a>
                                <form action="{{ route('proyectos.destroy', $proyecto->proyecto_id) }}" method="POST" class="form-eliminar">
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
                        <td colspan="6" class="text-center py-5 text-muted">No hay proyectos registrados con ese criterio.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando {{ $proyectos->firstItem() ?? 0 }} a {{ $proyectos->lastItem() ?? 0 }} de {{ $proyectos->total() }} registros
            </small>
            <div>
                {{ $proyectos->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const deleteButtons = document.querySelectorAll('.btn-swal-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.form-eliminar');
                
                Swal.fire({
                    title: '¿Desea eliminar este proyecto?',
                    text: "Esta acción borrará el registro de forma permanente.",
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