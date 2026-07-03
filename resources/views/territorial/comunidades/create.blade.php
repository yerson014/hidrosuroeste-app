@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('comunidades.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Registrar Nueva Comunidad</h2>
    </div>

    <form action="{{ route('comunidades.store') }}" method="POST">
        @csrf

        {{-- Bloque de seguridad para mostrar todos los errores devueltos por el servidor --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 mb-4">
                <div class="fw-bold mb-1"><i data-lucide="alert-circle" class="me-2 text-danger"></i>Por favor verifique los siguientes campos:</div>
                <ul class="mb-0 ps-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold"><i data-lucide="map-pin" class="me-2 text-primary"></i>Ubicación y Nombre</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="parroquia_id" class="form-label fw-bold">Parroquia perteneciente <span class="text-danger">*</span></label>
                            <select class="form-select @error('parroquia_id') is-invalid @enderror" name="parroquia_id" id="parroquia_id" required>
                                <option value="">Seleccione una parroquia...</option>
                                @foreach($parroquias as $parroquia)
                                    <option value="{{ $parroquia->parroquia_id }}" {{ old('parroquia_id') == $parroquia->parroquia_id ? 'selected' : '' }}>
                                        {{ $parroquia->nombre }} ({{ $parroquia->municipio->nombre }})
                                    </option>
                                @endforeach
                            </select>
                            @error('parroquia_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre de la Comunidad <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" placeholder="Ej. El Mirador" value="{{ old('nombre') }}" required>
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sector" class="form-label fw-bold">Sector</label>
                            <input type="text" class="form-control" id="sector" name="sector" placeholder="Ej. Sector Bajo" value="{{ old('sector') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold"><i data-lucide="bar-chart-3" class="me-2 text-primary"></i>Demografía</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6 mb-2">
                                <label class="small fw-bold">Habitantes</label>
                                <input type="number" name="habitantes" class="form-control" value="{{ old('habitantes', 0) }}">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="small fw-bold">Familias</label>
                                <input type="number" name="familias" class="form-control" value="{{ old('familias', 0) }}">
                            </div>
                            <div class="col-4">
                                <label class="small text-muted">Hombres</label>
                                <input type="number" name="hombres" class="form-control form-control-sm" value="{{ old('hombres', 0) }}">
                            </div>
                            <div class="col-4">
                                <label class="small text-muted">Mujeres</label>
                                <input type="number" name="mujeres" class="form-control form-control-sm" value="{{ old('mujeres', 0) }}">
                            </div>
                            <div class="col-4">
                                <label class="small text-muted">Niños</label>
                                <input type="number" name="ninos" class="form-control form-control-sm" value="{{ old('ninos', 0) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Servicios y Características</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="usa_cisterna" id="usa_cisterna" {{ old('usa_cisterna') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="usa_cisterna">Usa Cisterna</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="agua_potable" id="agua_potable" {{ old('agua_potable') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="agua_potable">Agua Potable</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="zonas_silencio" id="zonas_silencio" {{ old('zonas_silencio') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="zonas_silencio">Zonas Silencio</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="tanques_grandes" id="tanques_grandes" {{ old('tanques_grandes') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tanques_grandes">Tanques Grandes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('comunidades.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5" style="background: var(--primary); border: none;">Guardar Comunidad</button>
        </div>
    </form>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection