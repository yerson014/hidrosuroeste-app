@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1000px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('voceros-historial.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Registro Histórico #{{ $registro->vocero_historial_id }}</h2>
    </div>

    <form action="{{ route('voceros-historial.update', $registro->vocero_historial_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-lucide="file-text" class="me-2 text-primary"></i>Datos del Vocero
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="vocero_id" class="form-label fw-bold">Vocero</label>
                            <select class="form-select @error('vocero_id') is-invalid @enderror" name="vocero_id" id="vocero_id" required>
                                @foreach($voceros as $vocero)
                                    <option value="{{ $vocero->vocero_id }}" {{ old('vocero_id', $registro->vocero_id) == $vocero->vocero_id ? 'selected' : '' }}>
                                        {{ $vocero->nombres }} {{ $vocero->apellidos }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="mesa_tecnica_id" class="form-label fw-bold">Mesa Técnica</label>
                            <select class="form-select @error('mesa_tecnica_id') is-invalid @enderror" name="mesa_tecnica_id" id="mesa_tecnica_id" required>
                                @foreach($mesas as $mesa)
                                    <option value="{{ $mesa->mesa_tecnica_id }}" {{ old('mesa_tecnica_id', $registro->mesa_tecnica_id) == $mesa->mesa_tecnica_id ? 'selected' : '' }}>
                                        {{ $mesa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="motivo_salida" class="form-label fw-bold">Motivo de Salida</label>
                            <textarea name="motivo_salida" id="motivo_salida" class="form-control" rows="3">{{ old('motivo_salida', $registro->motivo_salida) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-lucide="calendar" class="me-2 text-primary"></i>Fechas de Gestión
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label fw-bold">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $registro->fecha_inicio) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label fw-bold">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', $registro->fecha_fin) }}">
                        </div>

                        <div class="alert alert-warning py-2 small mt-4">
                            <i data-lucide="alert-circle" class="me-1" style="width: 14px;"></i>
                            Tenga en cuenta que modificar las fechas altera el reporte de antigüedad del vocero.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('voceros-historial.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5" style="background: var(--primary); border: none;">Actualizar Registro</button>
        </div>
    </form>
</div>
<script> if (typeof lucide !== 'undefined') { lucide.createIcons(); } </script>
@endsection