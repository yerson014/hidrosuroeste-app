@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1000px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('voceros-historial.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Registrar Vocero en Historial</h2>
    </div>

    <form action="{{ route('voceros-historial.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-lucide="user-plus" class="me-2 text-primary"></i>Asignación de Vocero
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="vocero_id" class="form-label fw-bold">Vocero</label>
                            <select class="form-select @error('vocero_id') is-invalid @enderror" name="vocero_id" id="vocero_id" required>
                                <option value="">Seleccione un vocero...</option>
                                @foreach($voceros as $vocero)
                                    <option value="{{ $vocero->vocero_id }}" {{ old('vocero_id') == $vocero->vocero_id ? 'selected' : '' }}>
                                        {{ $vocero->nombre }} {{ $vocero->apellido }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vocero_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mesa_tecnica_id" class="form-label fw-bold">Mesa Técnica</label>
                            <select class="form-select @error('mesa_tecnica_id') is-invalid @enderror" name="mesa_tecnica_id" id="mesa_tecnica_id" required>
                                <option value="">Seleccione una mesa...</option>
                                @foreach($mesas as $mesa)
                                    <option value="{{ $mesa->mesa_tecnica_id }}" {{ old('mesa_tecnica_id') == $mesa->mesa_tecnica_id ? 'selected' : '' }}>
                                        {{ $mesa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mesa_tecnica_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="motivo_salida" class="form-label fw-bold">Motivo de Salida (Si aplica)</label>
                            <textarea name="motivo_salida" id="motivo_salida" class="form-control" rows="3" placeholder="Indique por qué dejó el cargo o mesa...">{{ old('motivo_salida') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-lucide="calendar" class="me-2 text-primary"></i>Periodo
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label fw-bold">Fecha de Inicio</label>
                            <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                            @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label fw-bold">Fecha de Fin (Opcional)</label>
                            <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}">
                            @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="alert alert-info py-2 small mt-4">
                            <i data-lucide="info" class="me-1" style="width: 14px;"></i>
                            Registre los periodos en los que el vocero ha pertenecido a una mesa técnica específica.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('voceros-historial.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5" style="background: var(--primary); border: none;">Guardar Historial</button>
        </div>
    </form>
</div>
<script> if (typeof lucide !== 'undefined') { lucide.createIcons(); } </script>
@endsection