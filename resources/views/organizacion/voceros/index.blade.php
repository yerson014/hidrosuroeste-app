@extends('layouts.app')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="users" class="me-2"></i>Gestión de Voceros
        </h4>
        <a href="{{ route('voceros.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
            <i data-lucide="plus" class="me-2"></i> Nuevo Vocero
        </a>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Excelente!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    <div class="panel-card" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <!-- Barra de Búsqueda -->
        <div class="p-4 border-bottom">
            <form action="{{ route('voceros.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" 
                                   name="buscar" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar por nombre, cédula o teléfono..." 
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
                        <a href="{{ route('voceros.index') }}" class="btn btn-light w-100">
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
                        <th class="ps-4 py-3">Cédula</th>
                        <th class="py-3">Vocero</th>
                        <th class="py-3">Teléfono</th>
                        <th class="py-3">Género</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voceros as $vocero)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ $vocero->cedula }}</td>
                        <td>
                            <div class="fw-bold" style="color: var(--primary);">{{ $vocero->nombre }} {{ $vocero->apellido }}</div>
                            <small class="text-muted">
                                <i data-lucide="map-pin" style="width: 10px;"></i> {{ Str::limit($vocero->direccion ?? 'Sin dirección', 30) }}
                            </small>
                        </td>
                        <td>
                            <small class="text-muted">{{ $vocero->telefono ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="text-capitalize small">{{ $vocero->genero ?? 'No definido' }}</span>
                        </td>
                        <td>
                            @if($vocero->estado == 'activo')
                                <span class="badge bg-success text-white">Activo</span>
                            @else
                                <span class="badge bg-secondary text-white">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('voceros.edit', $vocero->vocero_id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i data-lucide="edit-3" style="width: 16px;"></i>
                                </a>
                                <form action="{{ route('voceros.destroy', $vocero->vocero_id) }}" method="POST" class="form-eliminar">
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
                        <td colspan="6" class="text-center py-5 text-muted">No hay voceros registrados con ese criterio.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando {{ $voceros->firstItem() ?? 0 }} a {{ $voceros->lastItem() ?? 0 }} de {{ $voceros->total() }} registros
            </small>
            <div>
                {{ $voceros->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
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
                    title: '¿Eliminar vocero?',
                    text: "Se borrarán los datos de este vocero definitivamente.",
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