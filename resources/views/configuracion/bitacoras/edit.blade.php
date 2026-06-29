@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('bitacoras.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Modificar Registro de Bitácora</h2>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('bitacoras.update', $bitacora->bitacora_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Acción</label>
                        <select name="accion" class="form-select" required>
                            <option value="crear" {{ $bitacora->accion == 'crear' ? 'selected' : '' }}>Crear</option>
                            <option value="modificar" {{ $bitacora->accion == 'modificar' ? 'selected' : '' }}>Modificar</option>
                            <option value="eliminar" {{ $bitacora->accion == 'eliminar' ? 'selected' : '' }}>Eliminar</option>
                            <option value="login" {{ $bitacora->accion == 'login' ? 'selected' : '' }}>Login</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Entidad</label>
                        <input type="text" name="entidad" class="form-control" value="{{ old('entidad', $bitacora->entidad) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="5" required>{{ old('descripcion', $bitacora->descripcion) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small">Fecha Original</label>
                        <input type="text" class="form-control bg-light" value="{{ $bitacora->fecha }}" readonly disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small">IP Origen</label>
                        <input type="text" class="form-control bg-light" value="{{ $bitacora->ip }}" readonly disabled>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('bitacoras.index') }}" class="btn btn-light px-4">Volver</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endsection