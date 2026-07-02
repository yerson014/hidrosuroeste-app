@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('consejos-comunales.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Consejo Comunal: {{ $consejo->nombre }}</h2>
    </div>

    <form action="{{ route('consejos-comunales.update', $consejo->consejo_comunal_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold"><i data-lucide="home" class="me-2 text-primary"></i>Datos Generales</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="comunidad_id" class="form-label fw-bold">Comunidad perteneciente <span class="text-danger">*</span></label>
                            <select class="form-select @error('comunidad_id') is-invalid @enderror" name="comunidad_id" id="comunidad_id" required>
                                <option value="">Seleccione una comunidad...</option>
                                @foreach($comunidades as $comunidad)
                                    <option value="{{ $comunidad->comunidad_id }}" 
                                        {{ old('comunidad_id', $consejo->comunidad_id) == $comunidad->comunidad_id ? 'selected' : '' }}>
                                        {{ $comunidad->nombre }} ({{ $comunidad->parroquia->nombre ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('comunidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre del Consejo Comunal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" placeholder="Ej. Voces Unidas del Mirador" value="{{ old('nombre', $consejo->nombre) }}" required>
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold"><i data-lucide="user" class="me-2 text-primary"></i>Responsable / Líder</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="lider_nombre" class="form-label fw-bold">Nombre del Líder</label>
                            <input type="text" name="lider_nombre" id="lider_nombre" class="form-control" placeholder="Nombre completo" value="{{ old('lider_nombre', $consejo->lider_nombre) }}">
                        </div>
                        <div class="mb-3">
                            <label for="lider_telefono" class="form-label fw-bold">Teléfono de Contacto</label>
                            <input type="text" name="lider_telefono" id="lider_telefono" class="form-control" placeholder="04XX-XXXXXXX" value="{{ old('lider_telefono', $consejo->lider_telefono) }}">
                        </div>
                        <div class="alert alert-warning py-2 small mt-3">
                            <i data-lucide="alert-circle" class="me-1" style="width: 14px;"></i>
                            Asegúrese de que los datos de contacto estén actualizados para la comunicación institucional.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('consejos-comunales.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5" style="background: var(--primary); border: none;">Actualizar Consejo Comunal</button>
        </div>
    </form>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection