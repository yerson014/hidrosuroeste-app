@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1000px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('mesas-tecnicas.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Mesa Técnica: {{ $mesa->nombre }}</h2>
    </div>

    <form action="{{ route('mesas-tecnicas.update', $mesa->mesa_tecnica_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-lucide="users" class="me-2 text-primary"></i>Datos de la Mesa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre de la Mesa Técnica <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $mesa->nombre) }}" required>
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="consejo_comunal_id" class="form-label fw-bold">Consejo Comunal <span class="text-danger">*</span></label>
                            <select class="form-select @error('consejo_comunal_id') is-invalid @enderror" name="consejo_comunal_id" id="consejo_comunal_id" required>
                                @foreach($consejos as $consejo)
                                    <option value="{{ $consejo->consejo_comunal_id }}" {{ old('consejo_comunal_id', $mesa->consejo_comunal_id) == $consejo->consejo_comunal_id ? 'selected' : '' }}>
                                        {{ $consejo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('consejo_comunal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="centro_asociado_id" class="form-label fw-bold">Centro Asociado (Opcional)</label>
                            <select class="form-select @error('centro_asociado_id') is-invalid @enderror" name="centro_asociado_id" id="centro_asociado_id">
                                <option value="">Ninguno / No aplica</option>
                                @foreach($centros as $centro)
                                    <option value="{{ $centro->centro_asociado_id }}" {{ old('centro_asociado_id', $mesa->centro_asociado_id) == $centro->centro_asociado_id ? 'selected' : '' }}>
                                        {{ $centro->nombre }} ({{ $centro->tipo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('centro_asociado_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label fw-bold">Dirección Específica</label>
                            <textarea name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror" rows="2">{{ old('direccion', $mesa->direccion) }}</textarea>
                            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-lucide="settings" class="me-2 text-primary"></i>Configuración
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="estado" class="form-label fw-bold">Estado Operativo <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="activa" {{ old('estado', $mesa->estado) == 'activa' ? 'selected' : '' }}>Activa</option>
                                <option value="inactiva" {{ old('estado', $mesa->estado) == 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                            </select>
                            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fecha_creacion" class="form-label fw-bold">Fecha de Constitución</label>
                            <input type="date" class="form-control @error('fecha_creacion') is-invalid @enderror" id="fecha_creacion" name="fecha_creacion" value="{{ old('fecha_creacion', $mesa->fecha_creacion ? \Carbon\Carbon::parse($mesa->fecha_creacion)->format('Y-m-d') : '') }}">
                            @error('fecha_creacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="numero_integrantes" class="form-label fw-bold">N° de Integrantes</label>
                            <input type="number" class="form-control @error('numero_integrantes') is-invalid @enderror" id="numero_integrantes" name="numero_integrantes" min="0" max="12" value="{{ old('numero_integrantes', $mesa->numero_integrantes ?? 0) }}">
                            @error('numero_integrantes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="alert alert-warning py-2 small mt-4">
                            <i data-lucide="alert-triangle" class="me-1" style="width: 14px;"></i>
                            Atención: No se permiten nombres duplicados en el sistema.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('mesas-tecnicas.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5" style="background: var(--primary); border: none;">Actualizar Mesa Técnica</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        const inputIntegrantes = document.getElementById('numero_integrantes');
        inputIntegrantes.addEventListener('input', function() {
            if (this.value > 12) this.value = 12;
            if (this.value < 0) this.value = 0;
        });
    });
</script>
@endsection