@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('bitacoras.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Registrar Actividad Manual</h2>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('bitacoras.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Acción Realizada</label>
                        <select name="accion" class="form-select @error('accion') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            <option value="crear" {{ old('accion') == 'crear' ? 'selected' : '' }}>Crear</option>
                            <option value="modificar" {{ old('accion') == 'modificar' ? 'selected' : '' }}>Modificar</option>
                            <option value="eliminar" {{ old('accion') == 'eliminar' ? 'selected' : '' }}>Eliminar</option>
                            <option value="otro" {{ old('accion') == 'otro' ? 'selected' : '' }}>Otro / Especial</option>
                        </select>
                        @error('accion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Entidad / Módulo</label>
                        <input type="text" name="entidad" class="form-control @error('entidad') is-invalid @enderror" placeholder="Ej: Usuarios, Reportes, Inventario" value="{{ old('entidad') }}" required>
                        @error('entidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción de la Actividad</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="5" placeholder="Explique detalladamente la acción..." required>{{ old('descripcion') }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('bitacoras.index') }}" class="btn btn-light px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endsection