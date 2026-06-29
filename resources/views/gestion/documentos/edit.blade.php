@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('documentos.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Documento</h2>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('documentos.update', $documento->documento_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Relacionado con</label>
                        <select name="entidad" class="form-select" required>
                            <option value="mesa_tecnica" {{ $documento->entidad == 'mesa_tecnica' ? 'selected' : '' }}>Mesa Técnica del Agua</option>
                            <option value="proyecto" {{ $documento->entidad == 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                            <option value="incidencia" {{ $documento->entidad == 'incidencia' ? 'selected' : '' }}>Incidencia</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Reemplazar Archivo (Opcional)</label>
                        <input type="file" name="archivo" class="form-control">
                        <small class="text-primary">Actual: <a href="{{ asset('storage/' . $documento->url_archivo) }}" target="_blank">Ver archivo actual</a></small>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $documento->descripcion) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('documentos.index') }}" class="btn btn-light px-4">Volver</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar Documento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection