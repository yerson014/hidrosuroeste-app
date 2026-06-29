@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1000px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('usuarios.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left" style="color: var(--primary);"></i>
        </a>
        <h4 class="fw-bold mb-0" style="color: var(--primary);">
            <i data-lucide="user-cog" class="me-2"></i>Editar Perfil de Usuario
        </h4>
    </div>

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
            <i data-lucide="alert-circle" class="me-2" style="width:18px"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                         style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(45deg, var(--primary), #6366f1); font-size: 2rem;">
                        {{ strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellido, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $usuario->nombre }} {{ $usuario->apellido }}</h5>
                    <p class="text-muted small mb-3">{{ $usuario->correo }}</p>
                    <span class="badge {{ $usuario->rol == 'administrador' ? 'bg-danger' : 'bg-primary' }} px-3 py-2">
                        Rol: {{ ucfirst($usuario->rol) }}
                    </span>
                    <hr class="my-4">
                    <div class="text-start small text-muted">
                        <p class="mb-1"><i data-lucide="calendar" class="me-2" style="width: 14px;"></i>Registrado: {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : 'N/A' }}</p>
                        <p class="mb-0"><i data-lucide="key" class="me-2" style="width: 14px;"></i>ID de Usuario: #{{ $usuario->usuario_id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <form action="{{ route('usuarios.update', $usuario->usuario_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="text-uppercase text-muted fw-bold mb-4 small" style="letter-spacing: 1px;">Información Personal</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre', $usuario->nombre) }}" required>
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Apellido</label>
                                <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" 
                                       value="{{ old('apellido', $usuario->apellido) }}" required>
                                @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cédula</label>
                                <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" 
                                       value="{{ old('cedula', $usuario->cedula) }}" required>
                                @error('cedula') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" 
                                       value="{{ old('correo', $usuario->correo) }}" required>
                                @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rol del Sistema</label>
                                <select name="rol" class="form-select @error('rol') is-invalid @enderror" required>
                                    <option value="usuario" {{ old('rol', $usuario->rol) == 'usuario' ? 'selected' : '' }}>Usuario Estándar</option>
                                    <option value="administrador" {{ old('rol', $usuario->rol) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Dejar en blanco para no cambiar">
                                <small class="text-muted" style="font-size: 0.75rem;">Mínimo 8 caracteres si desea actualizar.</small>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-light px-4" style="border-radius: 8px;">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" style="background: var(--primary); border: none; border-radius: 8px;">
                                <i data-lucide="save" class="me-2" style="width: 18px;"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection