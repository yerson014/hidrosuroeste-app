@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--primary);">
            <i data-lucide="shield-check" class="me-2"></i>Gestión de Usuarios
        </h4>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary d-flex align-items-center" style="background: var(--primary); border: none; border-radius: 8px; padding: 10px 20px;">
            <i data-lucide="user-plus" class="me-2"></i> Nuevo Usuario
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form action="{{ route('usuarios.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-lucide="search" style="width: 18px; color: #666;"></i>
                            </span>
                            <input type="text" name="buscar" class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar por nombre, cédula o correo..." value="{{ $buscar ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100" style="background: var(--secondary); border: none;">Filtrar</button>
                    </div>
                    @if($buscar)
                    <div class="col-md-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-light w-100">Limpiar</a>
                    </div>
                    @endif
                </div>
            </form>
        </div>
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

    @if(session('error_relacion'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Operación Cancelada!',
            text: "{{ session('error_relacion') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Entendido'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Operación Cancelada!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Entendido'
        });
    </script>
    @endif

    <div class="row g-4">
        @forelse($usuarios as $user)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius: 15px; transition: transform 0.2s;">
                <div class="card-body">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                         style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(45deg, var(--primary), #6366f1); font-size: 1.5rem;">
                        {{ strtoupper(substr($user->nombre, 0, 1) . substr($user->apellido, 0, 1)) }}
                    </div>

                    <h5 class="fw-bold mb-1" style="color: #333;">{{ $user->nombre }} {{ $user->apellido }}</h5>
                    <div class="badge mb-3 {{ $user->rol == 'administrador' ? 'bg-danger' : ($user->rol == 'usuario' ? 'bg-primary' : 'bg-info') }} text-white">
                        {{ ucfirst($user->rol) }}
                    </div>

                    <div class="text-muted small mb-2">
                        <i data-lucide="mail" class="me-1" style="width: 12px;"></i> {{ $user->correo }}
                    </div>
                    <div class="text-muted small mb-3">
                        <i data-lucide="credit-card" class="me-1" style="width: 12px;"></i> {{ $user->cedula }}
                    </div>

                    <div class="border-top pt-3 d-flex justify-content-center gap-2">
                        <a href="{{ route('usuarios.edit', $user->usuario_id) }}" class="btn btn-sm btn-outline-primary rounded-circle p-2" title="Editar">
                            <i data-lucide="edit-2" style="width: 16px;"></i>
                        </a>
                        
                        <form action="{{ route('usuarios.destroy', $user->usuario_id) }}" method="POST" id="delete-form-{{ $user->usuario_id }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-2 btn-delete" data-id="{{ $user->usuario_id }}" title="Eliminar">
                                <i data-lucide="trash-2" style="width: 16px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="text-muted">
                <i data-lucide="users" style="width: 48px; height: 48px;" class="mb-3"></i>
                <p>No se encontraron usuarios registrados.</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $usuarios->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar iconos de Lucide
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Lógica de SweetAlert para Eliminación (Basada exactamente en tu historial de voceros)
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const form = document.getElementById(`delete-form-${id}`);

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se eliminará permanentemente este usuario del sistema.",
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