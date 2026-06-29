@extends('layouts.app')

@section('content')
<!-- Importar SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="history" class="me-2"></i>Historial de Mesas Técnicas
        </h4>
        <a href="{{ route('mesas-historial.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
            <i data-lucide="plus" class="me-2"></i> Nuevo Registro
        </a>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Logrado!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    <div class="panel-card" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <!-- Barra de Búsqueda -->
        <div class="p-4 border-bottom">
            <form action="{{ route('mesas-historial.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" 
                                   name="buscar" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar por descripción o mesa..." 
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
                        <a href="{{ route('mesas-historial.index') }}" class="btn btn-light w-100">
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
                        <th class="py-3">Fecha</th>
                        <th class="py-3">Mesa Técnica</th>
                        <th class="py-3">Descripción</th>
                        {{-- <th class="py-3 text-center">Acciones</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($historial as $item)
                    <tr>
                        <td class="ps-4">#{{ $item->mesa_historial_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y h:i A') }}</td>
                        <td>
                            <div class="fw-bold" style="color: var(--primary);">{{ $item->mesaTecnica->nombre ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $item->descripcion }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No hay registros en el historial.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando {{ $historial->firstItem() ?? 0 }} a {{ $historial->lastItem() ?? 0 }} de {{ $historial->total() }} registros
            </small>
            <div>
                {{ $historial->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Iconos Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Manejador de eliminación con SweetAlert2
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const registroId = this.getAttribute('data-id');
                const form = document.getElementById(`delete-form-${registroId}`);

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Este registro del historial de la MTA se eliminará permanentemente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminarlo',
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