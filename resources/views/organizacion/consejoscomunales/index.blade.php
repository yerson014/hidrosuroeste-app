@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="users-2" class="me-2"></i>Gestión de Consejos Comunales
        </h4>
        <a href="{{ route('consejos-comunales.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
            <i data-lucide="plus" class="me-2"></i> Nuevo Consejo
        </a>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Hecho!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    {{-- Captura de operación cancelada por relación con Mesa Técnica --}}
    @if(session('error_relacion'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Operación Cancelada!',
            text: @json(session('error_relacion')),
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Entendido'
        });
    </script>
    @endif

    <div class="panel-card" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <div class="p-4 border-bottom">
            <form action="{{ route('consejos-comunales.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" 
                                   name="buscar" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar por nombre de consejo o líder..." 
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
                        <a href="{{ route('consejos-comunales.index') }}" class="btn btn-light w-100">
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
                        <th class="py-3">Consejo Comunal</th>
                        <th class="py-3">Comunidad</th>
                        <th class="py-3">Líder / Contacto</th>
                        <th class="py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consejos as $consejo)
                    <tr>
                        <td class="ps-4">#{{ $consejo->consejo_comunal_id }}</td>
                        <td>
                            <div class="fw-bold" style="color: var(--primary);">{{ $consejo->nombre }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $consejo->comunidad->nombre ?? 'Sin comunidad' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $consejo->lider_nombre ?? 'No asignado' }}</span>
                                <small class="text-muted"><i data-lucide="phone" class="me-1" style="width: 12px;"></i>{{ $consejo->lider_telefono ?? 'S/T' }}</small>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('consejos-comunales.edit', $consejo->consejo_comunal_id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i data-lucide="edit-3" style="width: 16px;"></i>
                                </a>
                                <form action="{{ route('consejos-comunales.destroy', $consejo->consejo_comunal_id) }}" method="POST" class="form-eliminar">
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
                        <td colspan="5" class="text-center py-5 text-muted">No hay consejos comunales registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando {{ $consejos->firstItem() ?? 0 }} a {{ $consejos->lastItem() ?? 0 }} de {{ $consejos->total() }} registros
            </small>
            <div>
                {{ $consejos->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
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
                    title: '¿Confirmar eliminación?',
                    text: "Esta acción borrará el consejo comunal del sistema.",
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