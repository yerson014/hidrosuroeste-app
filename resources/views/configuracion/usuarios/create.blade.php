@extends('layouts.app')

@section('content')
<style>
    .required-label::after {
        content: " *";
        color: #ef4444; /* Rojo suave */
        font-weight: bold;
    }
</style>

<div class="container-fluid" style="max-width: 1000px;">
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <a href="{{ route('usuarios.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
                <i data-lucide="arrow-left" style="color: var(--primary);"></i>
            </a>
            <h4 class="fw-bold mb-0" style="color: var(--primary);">
                <i data-lucide="user-plus" class="me-2"></i>Registrar Nuevo Usuario
            </h4>
        </div>
    </div>

    {{-- Errores Generales --}}
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
                         style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(45deg, #4f46e5, #06b6d4); font-size: 2rem;">
                        <i data-lucide="user" style="width: 50px; height: 50px;"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Nuevo Registro</h5>
                    <p class="text-muted small mb-3">Complete el formulario para crear un acceso al sistema.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf

                        <h6 class="text-uppercase text-muted fw-bold mb-4 small" style="letter-spacing: 1px;">Información de Perfil</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-label">Apellido</label>
                                <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" required>
                                @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-label">Cédula de Identidad</label>
                                <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula') }}" required>
                                @error('cedula') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-label">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}" required>
                                @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold required-label">Correo Electrónico</label>
                                <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required>
                                @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <h6 class="text-uppercase text-muted fw-bold mt-5 mb-3 small" style="letter-spacing: 1px;">Seguridad y Acceso</h6>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold required-label">Asignar Rol</label>
                                <select name="rol" class="form-select @error('rol') is-invalid @enderror" required>
                                    <option value="" selected disabled>Seleccione un nivel...</option>
                                    <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                                    <option value="usuario" {{ old('rol') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                                </select>
                                @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-label">Contraseña</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4 border-0">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" style="background: var(--primary); border: none; border-radius: 10px;">
                                <i data-lucide="save" class="me-2" style="width: 18px;"></i> Guardar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection