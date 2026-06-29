@extends('layouts.app')

@section('content')
<style>
    .required-asterisk { color: #dc3545; margin-left: 3px; }
</style>

<div class="container-fluid" style="max-width: 1200px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('proyectos.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Registrar Proyecto</h2>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('proyectos.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Título del Proyecto<span class="required-asterisk">*</span></label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" placeholder="Ej: Sustitución de 12 metros de tubería matriz de 20" value="{{ old('titulo') }}" required>
                        @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado<span class="required-asterisk">*</span></label>
                        <select name="estado" class="form-select" required>
                            <option value="propuesto" {{ old('estado') == 'propuesto' ? 'selected' : '' }}>Propuesto</option>
                            <option value="aprobado" {{ old('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="ejecutado" {{ old('estado') == 'ejecutado' ? 'selected' : '' }}>Ejecutado</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mesa Técnica Responsable<span class="required-asterisk">*</span></label>
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
                        <label class="form-label fw-bold">Fecha de Registro</label>
                        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Ubicación / Dirección</label>
                        <input type="text" name="ubicacion" class="form-control" placeholder="Indique la zona o sector del proyecto" value="{{ old('ubicacion') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción del Proyecto</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Detalle los objetivos y alcances del proyecto...">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('proyectos.index') }}" class="btn btn-light px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Guardar Proyecto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endsection