@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="users" class="me-2"></i>Historial de Voceros
        </h4>
        <a href="{{ route('voceros-historial.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
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
        <div class="p-4 border-bottom">
            <form action="{{ route('voceros-historial.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" name="buscar" class="form-control border-start-0 ps-0" placeholder="Buscar vocero o motivo..." value="{{ $buscar ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100" style="background: var(--secondary); border: none;">Filtrar</button>
                    </div>
                    @if($buscar)
                    <div class="col-md-2">
                        <a href="{{ route('voceros-historial.index') }}" class="btn btn-light w-100">Limpiar</a>
                    </div>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Vocero</th>
                        <th class="py-3">Mesa Técnica</th>
                        <th class="py-3">Periodo</th>
                        <th class="py-3">Motivo de Salida</th>
                        {{-- <th class="py-3 text-center">Acciones</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($historial as $item)
                    <tr>
                        <td class="ps-4">
                            <!-- Ajustado a 'nombre' y 'apellido' -->
                            <div class="fw-bold text-dark">{{ $item->vocero->nombre ?? 'N/A' }} {{ $item->vocero->apellido ?? '' }}</div>
                            <small class="text-muted">Historial ID: #{{ $item->vocero_historial_id }}</small>
                        </td>
                        <td>{{ $item->mesaTecnica->nombre ?? 'N/A' }}</td>
                        <td>
                            <div class="small">
                                <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y') }}<br>
                                <strong>Fin:</strong> {{ $item->fecha_fin ? \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/Y') : 'En curso' }}
                            </div>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $item->motivo_salida ?? 'Sin motivo' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No hay registros de historial.</td>
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
                {{ $historial->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }

        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const form = document.getElementById(`delete-form-${id}`);

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se eliminará permanentemente este registro del historial del vocero.",
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