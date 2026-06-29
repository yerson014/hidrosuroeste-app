@extends('layouts.app')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="users" class="me-2"></i>Gestión de Mesas Técnicas
        </h4>
        <a href="{{ route('mesas-tecnicas.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
            <i data-lucide="plus" class="me-2"></i> Nueva Mesa
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

    {{-- Captura de operación cancelada debido a relaciones o dependencias --}}
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
        <!-- Barra de Búsqueda -->
        <div class="p-4 border-bottom">
            <form action="{{ route('mesas-tecnicas.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" 
                                   name="buscar" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar por nombre, estado o dirección..." 
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
                        <a href="{{ route('mesas-tecnicas.index') }}" class="btn btn-light w-100">
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
                        <th class="py-3">Mesa Técnica</th>
                        <th class="py-3">Consejo Comunal</th>
                        <th class="py-3">Centro Asociado</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mesas as $mesa)
                    <tr>
                        <td class="ps-4">#{{ $mesa->mesa_tecnica_id }}</td>
                        <td>
                            <div class="fw-bold" style="color: var(--primary);">{{ $mesa->nombre }}</div>
                            <small class="text-muted">
                                <i data-lucide="map-pin" style="width: 10px;"></i> {{ Str::limit($mesa->direccion ?? 'Sin dirección', 25) }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $mesa->consejoComunal->nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $mesa->centroAsociado->nombre ?? 'No vinculado' }}
                            </small>
                        </td>
                        <td>
                            @if($mesa->estado == 'activa')
                                <span class="badge bg-success text-white">Activa</span>
                            @else
                                <span class="badge bg-secondary text-white">Inactiva</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <!-- BOTÓN PDF ADICIONADO -->
                                <a href="{{ route('mesas-tecnicas.pdf', $mesa->mesa_tecnica_id) }}" class="btn btn-sm btn-outline-primary" title="Descargar PDF" target="_blank">
                                    <i data-lucide="file-text" style="width: 16px;"></i>
                                </a>
                                
                                <a href="{{ route('mesas-tecnicas.edit', $mesa->mesa_tecnica_id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i data-lucide="edit-3" style="width: 16px;"></i>
                                </a>
                                <form action="{{ route('mesas-tecnicas.destroy', $mesa->mesa_tecnica_id) }}" method="POST" class="form-eliminar">
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
                        <td colspan="6" class="text-center py-5 text-muted">No hay mesas técnicas registradas con ese criterio.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando {{ $mesas->firstItem() ?? 0 }} a {{ $mesas->lastItem() ?? 0 }} de {{ $mesas->total() }} registros
            </small>
            <div>
                {{ $mesas->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
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
                    title: '¿Desea eliminar esta Mesa Técnica?',
                    text: "Se eliminará permanentemente este registro de la mesa técnica.",
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