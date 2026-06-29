@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('municipios.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Municipio: {{ $municipio->nombre }}</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('municipios.update', $municipio->municipio_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">
                        Nombre del Municipio <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('nombre') is-invalid @enderror" 
                            id="nombre" 
                            name="nombre" 
                            required>
                        @php
                            $municipiosTachira = [
                                'Andrés Bello', 'Antonio Rómulo Costa', 'Ayacucho', 'Bolívar', 'Cárdenas',
                                'Córdoba', 'Fernández Feo', 'Francisco de Miranda', 'García de Hevia',
                                'Guásimos', 'Independencia', 'Jáuregui', 'José María Vargas', 'Junín',
                                'Libertad', 'Libertador', 'Lobatera', 'Michelena', 'Panamericano',
                                'Pedro María Ureña', 'Rafael Urdaneta', 'Samuel Darío Maldonado',
                                'San Cristóbal', 'San Judas Tadeo', 'Seboruco', 'Simón Rodríguez',
                                'Sucre', 'Torbes', 'Uribante'
                            ];
                        @endphp
                        @foreach($municipiosTachira as $muni)
                            <option value="{{ $muni }}" {{ old('nombre', $municipio->nombre) == $muni ? 'selected' : '' }}>
                                {{ $muni }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Aquí se mostrará el error si intentas cambiarlo por uno que ya existe -->
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('municipios.index') }}" class="btn btn-light px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar Municipio</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endsection