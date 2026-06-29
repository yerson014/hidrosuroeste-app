@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('comunas.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Comuna: {{ $comuna->nombre }}</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('comunas.update', $comuna->comuna_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="parroquia_id" class="form-label fw-bold">Parroquia perteneciente <span class="text-danger">*</span></label>
                    <select class="form-select @error('parroquia_id') is-invalid @enderror" \
                            name="parroquia_id" \
                            id="parroquia_id" \
                            required>
                        <option value="">Seleccione una parroquia...</option>
                        @foreach($parroquias as $parroquia)
                            <option value="{{ $parroquia->parroquia_id }}" \
                                {{ old('parroquia_id', $comuna->parroquia_id) == $parroquia->parroquia_id ? 'selected' : '' }}>
                                {{ $parroquia->nombre }} (Muni. {{ $parroquia->municipio->nombre }})
                            </option>
                        @endforeach
                    </select>
                    @error('parroquia_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="comunidad_id" class="form-label fw-bold">Comunidad asociada <span class="text-muted">(Opcional)</span></label>
                    <select class="form-select @error('comunidad_id') is-invalid @enderror" \
                            name="comunidad_id" \
                            id="comunidad_id">
                        <option value="">Seleccione una comunidad...</option>
                        @foreach($comunidades as $comunidad)
                            <option value="{{ $comunidad->comunidad_id }}" \
                                {{ old('comunidad_id', $comuna->comunidad_id) == $comunidad->comunidad_id ? 'selected' : '' }}>
                                {{ $comunidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('comunidad_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre de la Comuna <span class="text-danger">*</span></label>
                    <input type="text" \
                           class="form-control @error('nombre') is-invalid @enderror" \
                           id="nombre" \
                           name="nombre" \
                           placeholder="Ej. Comuna Guerrera del Táchira" \
                           value="{{ old('nombre', $comuna->nombre) }}" \
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('comunas.index') }}" class="btn btn-light px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar Comuna</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection