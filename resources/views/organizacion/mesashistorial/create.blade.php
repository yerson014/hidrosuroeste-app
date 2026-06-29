@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1000px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('mesas-historial.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Registrar MTA en Historial</h2>
    </div>

    <form action="{{ route('mesas-historial.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-lucide="file-text" class="me-2 text-primary"></i>Detalle de MTA
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="mesa_tecnica_id" class="form-label fw-bold">Mesa Técnica</label>
                            <select class="form-select @error('mesa_tecnica_id') is-invalid @enderror" name="mesa_tecnica_id" id="mesa_tecnica_id" required>
                                <option value="">Seleccione una mesa técnica...</option>
                                @foreach($mesas as $mesa)
                                    <option value="{{ $mesa->mesa_tecnica_id }}" {{ old('mesa_tecnica_id') == $mesa->mesa_tecnica_id ? 'selected' : '' }}>
                                        {{ $mesa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mesa_tecnica_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold">Descripción del Evento</label>
                            <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="5" placeholder="Describa lo ocurrido (reunión, mantenimiento, cambio de directiva, etc.)" required>{{ old('descripcion') }}</textarea>
                            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-lucide="calendar" class="me-2 text-primary"></i>Temporalidad
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="fecha" class="form-label fw-bold">Fecha y Hora del Evento</label>
                            <input type="datetime-local" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d\TH:i')) }}">
                            @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="alert alert-info py-2 small mt-4">
                            <i data-lucide="info" class="me-1" style="width: 14px;"></i>
                            El historial permite llevar una bitácora detallada de las actividades de cada mesa técnica.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('mesas-historial.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5" style="background: var(--primary); border: none;">Guardar en Historial</button>
        </div>
    </form>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection