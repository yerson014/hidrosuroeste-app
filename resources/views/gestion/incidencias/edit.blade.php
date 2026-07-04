@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('incidencias.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Incidencia</h2>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('incidencias.update', $incidencia->incidencia_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Título de la Incidencia <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $incidencia->titulo) }}" required>
                        @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado Actual <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select" required>
                            <option value="registrada" {{ old('estado', $incidencia->estado) == 'registrada' ? 'selected' : '' }}>Registrada</option>
                            <option value="atendida" {{ old('estado', $incidencia->estado) == 'atendida' ? 'selected' : '' }}>Atendida</option>
                            <option value="resuelta" {{ old('estado', $incidencia->estado) == 'resuelta' ? 'selected' : '' }}>Resuelta</option>
                            <option value="suspendida" {{ old('estado', $incidencia->estado) == 'suspendida' ? 'selected' : '' }}>Suspendida</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mesa Técnica <span class="text-danger">*</span></label>
                        <select name="mesa_tecnica_id" class="form-select" required>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->mesa_tecnica_id }}" {{ old('mesa_tecnica_id', $incidencia->mesa_tecnica_id) == $mesa->mesa_tecnica_id ? 'selected' : '' }}>
                                    {{ $mesa->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Comunidad <span class="text-danger">*</span></label>
                        <select name="comunidad_id" class="form-select" required>
                            @foreach($comunidades as $comunidad)
                                <option value="{{ $comunidad->comunidad_id }}" {{ old('comunidad_id', $incidencia->comunidad_id) == $comunidad->comunidad_id ? 'selected' : '' }}>
                                    {{ $comunidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="filtracion externa" {{ old('tipo', $incidencia->tipo) == 'filtracion externa' ? 'selected' : '' }}>Filtración Externa</option>
                            <option value="averia de red" {{ old('tipo', $incidencia->tipo) == 'averia de red' ? 'selected' : '' }}>Avería de Red</option>
                            <option value="baja presion / escasez" {{ old('tipo', $incidencia->tipo) == 'baja presion / escasez' ? 'selected' : '' }}>Baja Presión / Escasez</option>
                            <option value="daño de infraestructura" {{ old('tipo', $incidencia->tipo) == 'daño de infraestructura' ? 'selected' : '' }}>Damage de Infraestructura</option>
                            <option value="filtracion / rotura" {{ old('tipo', $incidencia->tipo) == 'filtracion / rotura' ? 'selected' : '' }}>Filtración / Rotura</option>
                            <option value="fuga de agua" {{ old('tipo', $incidencia->tipo) == 'fuga de agua' ? 'selected' : '' }}>Fuga de Agua</option>
                            <option value="suministro" {{ old('tipo', $incidencia->tipo) == 'suministro' ? 'selected' : '' }}>Suministro</option>
                            <option value="aguas servidas" {{ old('tipo', $incidencia->tipo) == 'aguas servidas' ? 'selected' : '' }}>Aguas Servidas</option>
                            <option value="agua potable" {{ old('tipo', $incidencia->tipo) == 'agua potable' ? 'selected' : '' }}>Agua Potable</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Prioridad</label>
                        <select name="prioridad" class="form-select">
                            <option value="baja" {{ old('prioridad', $incidencia->prioridad) == 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ old('prioridad', $incidencia->prioridad) == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ old('prioridad', $incidencia->prioridad) == 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $incidencia->fecha) }}" @error('fecha') is-invalid @enderror">
                        @error('fecha')
                            <span class="text-danger small fw-bold">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('incidencias.index') }}" class="btn btn-light px-4">Volver</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar Incidencia</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection