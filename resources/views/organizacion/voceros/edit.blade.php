@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('voceros.index') }}" class="text-decoration-none text-muted small">
            <i data-lucide="arrow-left" style="width: 14px;"></i> Volver al listado
        </a>
        <h4 class="fw-bold mt-2" style="color: var(--primary);">
            <i data-lucide="user-cog" class="me-2"></i>Editar Vocero: {{ $vocero->nombre }} {{ $vocero->apellido }}
        </h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form action="{{ route('voceros.update', $vocero->vocero_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-12 border-bottom pb-2 mb-2">
                                <h6 class="text-muted text-uppercase fw-bold small">Información Personal</h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Cédula <span class="text-danger">*</span></label>
                                <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula', $vocero->cedula) }}" required placeholder="V-00000000">
                                @error('cedula') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $vocero->nombre) }}" required>
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $vocero->apellido) }}" required>
                                @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Género <span class="text-danger">*</span></label>
                                <select name="genero" class="form-select @error('genero') is-invalid @enderror" required>
                                    <option value="Masculino" {{ old('genero', $vocero->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ old('genero', $vocero->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ old('genero', $vocero->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Teléfono</label>
                                <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $vocero->telefono) }}" placeholder="04XX-0000000">
                                @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Estado <span class="text-danger">*</span></label>
                                <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                    <option value="activo" {{ old('estado', $vocero->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estado', $vocero->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 border-bottom pb-2 mb-2 mt-4">
                                <h6 class="text-muted text-uppercase fw-bold small">Ubicación y Detalles</h6>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small">Dirección de Habitación</label>
                                <textarea name="direccion" class="form-control @error('direccion') is-invalid @enderror" rows="3" placeholder="Indique la dirección detallada...">{{ old('direccion', $vocero->direccion) }}</textarea>
                                @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 gap-2">
                            <a href="{{ route('voceros.index') }}" class="btn btn-light px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-5" style="background: var(--primary); border: none; border-radius: 8px;">
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
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection