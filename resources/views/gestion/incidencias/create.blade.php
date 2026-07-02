@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('incidencias.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Registrar Incidencia</h2>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('incidencias.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Título de la Incidencia <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" placeholder="Ej: Rotura de tubería matriz 12 pulgadas" value="{{ old('titulo') }}" required>
                        @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select" required>
                            <option value="suspendida" {{ old('estado') == 'suspendida' ? 'selected' : '' }}>Suspendida</option>
                            <option value="registrada" {{ old('estado') == 'registrada' ? 'selected' : '' }}>Registrada</option>
                            <option value="atendida" {{ old('estado') == 'atendida' ? 'selected' : '' }}>Atendida</option>
                            <option value="resuelta" {{ old('estado') == 'resuelta' ? 'selected' : '' }}>Resuelta</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mesa Técnica Responsable <span class="text-danger">*</span></label>
                        <select name="mesa_tecnica_id" class="form-select @error('mesa_tecnica_id') is-invalid @enderror" required>
                            <option value="">Seleccione una mesa...</option>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->mesa_tecnica_id }}" {{ old('mesa_tecnica_id') == $mesa->mesa_tecnica_id ? 'selected' : '' }}>
                                    {{ $mesa->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('mesa_tecnica_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Comunidad Afectada <span class="text-danger">*</span></label>
                        <select name="comunidad_id" class="form-select @error('comunidad_id') is-invalid @enderror" required>
                            <option value="">Seleccione una comunidad...</option>
                            @foreach($comunidades as $comunidad)
                                <option value="{{ $comunidad->comunidad_id }}" {{ old('comunidad_id') == $comunidad->comunidad_id ? 'selected' : '' }}>
                                    {{ $comunidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('comunidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo de Incidencia</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                            <option value="">Seleccione tipo...</option>
                            <option value="Fuga" {{ old('tipo') == 'Fuga' ? 'selected' : '' }}>Fuga de Agua</option>
                            <option value="Agua Potable" {{ old('tipo') == 'Agua Potable' ? 'selected' : '' }}>Agua Potable</option>
                            <option value="Servicio" {{ old('tipo') == 'Servicio' ? 'selected' : '' }}>Suministro / Servicio</option>
                            <option value="Aguas Servidas" {{ old('tipo') == 'Aguas Servidas' ? 'selected' : '' }}>Aguas Servidas</option>
                            <option value="Denuncia" {{ old('tipo') == 'Denuncia' ? 'selected' : '' }}>Denuncia / Toma Ilegal</option>
                            <option value="Infraestructura" {{ old('tipo') == 'Infraestructura' ? 'selected' : '' }}>Infraestructura / Tuberías</option>
                            <option value="Calidad" {{ old('tipo') == 'Calidad' ? 'selected' : '' }}>Calidad / Turbidez</option>
                            <option value="Otro" {{ old('tipo') == 'Otro' ? 'selected' : '' }}>Filtración Externa</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Prioridad</label>
                        <select name="prioridad" class="form-select">
                            <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha del Suceso</label>
                        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}">
                        
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción Detallada</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Describa el problema reportado...">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('incidencias.index') }}" class="btn btn-light px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Guardar Incidencia</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection