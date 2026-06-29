@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('proyectos.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Proyecto</h2>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            {{-- Mensaje de error general si el controlador detecta duplicados u otros fallos --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('proyectos.update', $proyecto->proyecto_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Título del Proyecto <span class="text-danger">*</span></label>
                        {{-- El título ahora marcará error si el controlador detecta que ya existe en otro registro --}}
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $proyecto->titulo) }}" required>
                        @error('titulo') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select" required>
                            <option value="propuesto" {{ old('estado', $proyecto->estado) == 'propuesto' ? 'selected' : '' }}>Propuesto</option>
                            <option value="aprobado" {{ old('estado', $proyecto->estado) == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="ejecutado" {{ old('estado', $proyecto->estado) == 'ejecutado' ? 'selected' : '' }}>Ejecutado</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mesa Técnica Responsable <span class="text-danger">*</span></label>
                        <select name="mesa_tecnica_id" class="form-select @error('mesa_tecnica_id') is-invalid @enderror" required>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->mesa_tecnica_id }}" {{ old('mesa_tecnica_id', $proyecto->mesa_tecnica_id) == $mesa->mesa_tecnica_id ? 'selected' : '' }}>
                                    {{ $mesa->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $proyecto->fecha) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" value="{{ old('ubicacion', $proyecto->ubicacion) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('proyectos.index') }}" class="btn btn-light px-4">Volver</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endsection