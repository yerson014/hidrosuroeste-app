@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('centros-asociados.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Centro Educativo: {{ $centro->nombre }}</h2>
    </div>

    <form action="{{ route('centros-asociados.update', $centro->centro_asociado_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold"><i data-lucide="building" class="me-2 text-primary"></i>Datos del Centro</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="comunidad_id" class="form-label fw-bold">Comunidad perteneciente <span class="text-danger">*</span></label>
                            <select class="form-select @error('comunidad_id') is-invalid @enderror" name="comunidad_id" id="comunidad_id" required>
                                <option value="">Seleccione una comunidad...</option>
                                @foreach($comunidades as $comunidad)
                                    <option value="{{ $comunidad->comunidad_id }}" 
                                        {{ old('comunidad_id', $centro->comunidad_id) == $comunidad->comunidad_id ? 'selected' : '' }}>
                                        {{ $comunidad->nombre }} ({{ $comunidad->parroquia->nombre ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('comunidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre de la Escuela / Centro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" 
                                   placeholder="Ej. Escuela Básica Nacional..." 
                                   value="{{ old('nombre', $centro->nombre) }}" required>
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ubicacion" class="form-label fw-bold">Dirección / Ubicación Técnica</label>
                            <textarea name="ubicacion" id="ubicacion" class="form-control" rows="2" 
                                      placeholder="Describa la ubicación exacta">{{ old('ubicacion', $centro->ubicacion) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold"><i data-lucide="layers" class="me-2 text-primary"></i>Clasificación</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="tipo" class="form-label fw-bold">Tipo de Centro</label>
                            <select name="tipo" id="tipo" class="form-select">
                                <option value="">Seleccione tipo...</option>
                                @php
                                    $tipos = ['Escuela', 'Liceo', 'UBCH', 'CDI', 'Módulo', 'Cancha', 'Otro'];
                                @endphp
                                @foreach($tipos as $tipoOp)
                                    <option value="{{ $tipoOp }}" {{ old('tipo', $centro->tipo) == $tipoOp ? 'selected' : '' }}>
                                        {{ $tipoOp == 'Módulo' ? 'Módulo Policial/Salud' : $tipoOp }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-info py-2 small">
                            <i data-lucide="info" class="me-1" style="width: 14px;"></i>
                            Está editando un registro existente. Asegúrese de que la clasificación sea la correcta para los reportes.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('centros-asociados.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5" style="background: var(--primary); border: none;">
                Actualizar Centro
            </button>
        </div>
    </form>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection