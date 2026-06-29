@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('documentos.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Subir Nuevo Documento</h2>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Relacionado con (Entidad)</label>
                        <select name="entidad" class="form-select @error('entidad') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            <option value="mesa_tecnica" {{ old('entidad') == 'mesa_tecnica' ? 'selected' : '' }}>Mesa Técnica del Agua</option>
                            <option value="proyecto" {{ old('entidad') == 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                            <option value="incidencia" {{ old('entidad') == 'incidencia' ? 'selected' : '' }}>Incidencia</option>
                        </select>
                        @error('entidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Archivo (PDF, Imágenes)</label>
                        <input type="file" name="archivo" class="form-control @error('archivo') is-invalid @enderror" required>
                        <small class="text-muted">Máximo 5MB. Formatos: PDF, DOCX, JPG, PNG.</small>
                        @error('archivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción del Documento</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Detalle el contenido del documento o lo que se ha hecho sobre la mesa técnica...">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('documentos.index') }}" class="btn btn-light px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Guardar y Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection