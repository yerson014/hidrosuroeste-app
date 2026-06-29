@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('parroquias.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i data-lucide="arrow-left"></i>
        </a>
        <h2 class="h4 mb-0">Editar Parroquia: {{ $parroquia->nombre }}</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('parroquias.update', $parroquia->parroquia_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="municipio_id" class="form-label fw-bold">Municipio perteneciente <span class="text-danger">*</span></label>
                    <select class="form-select @error('municipio_id') is-invalid @enderror" 
                            name="municipio_id" 
                            id="municipio_id" 
                            required>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->municipio_id }}" 
                                    data-nombre="{{ $municipio->nombre }}"
                                    {{ old('municipio_id', $parroquia->municipio_id) == $municipio->municipio_id ? 'selected' : '' }}>
                                {{ $municipio->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('municipio_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre de la Parroquia <span class="text-danger">*</span></label>
                    <select class="form-select @error('nombre') is-invalid @enderror" 
                            id="nombre" 
                            name="nombre" 
                            required>
                        <option value="">Seleccione una parroquia...</option>
                    </select>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('parroquias.index') }}" class="btn btn-light px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar Parroquia</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const parroquiasPorMunicipio = {
        'Andrés Bello': ['Cordero'],
        'Antonio Rómulo Costa': ['Las Mesas'],
        'Ayacucho': ['San Juan de Colón', 'San Pedro del Río', 'Rivas Berti'],
        'Bolívar': ['San Antonio del Táchira', 'Palotal', 'Juan Vicente Gómez', 'Isaías Medina Angarita'],
        'Cárdenas': ['Táriba', 'Amenodoro Rangel Lamús', 'La Concordia'],
        'Córdoba': ['Santa Ana del Táchira'],
        'Fernández Feo': ['San Rafael del Piñal', 'Alberto Adriani', 'Santo Domingo'],
        'Francisco de Miranda': ['San José de Bolívar'],
        'García de Hevia': ['La Fría', 'Boca de Grita', 'José Antonio Páez'],
        'Guásimos': ['Palmira'],
        'Independencia': ['Capacho Nuevo', 'Juan Germán Roscio', 'Román Cárdenas'],
        'Jáuregui': ['La Grita', 'Emilio Constantino Guerrero', 'Monseñor Miguel Antonio Salas'],
        'José María Vargas': ['El Cobre'],
        'Junín': ['Rubio', 'Bramón', 'La Petrolea', 'Quinimarí'],
        'Libertad': ['Capacho Viejo', 'Cipriano Castro', 'Manuel Felipe Rugeles'],
        'Libertador': ['Abejales', 'Emeterio Ochoa', 'Doradas', 'San Joaquín de Navay'],
        'Lobatera': ['Lobatera', 'Constitución'],
        'Michelena': ['Michelena'],
        'Panamericano': ['Coloncito', 'La Palmita'],
        'Pedro María Ureña': ['Ureña', 'Nueva Arcadia'],
        'Rafael Urdaneta': ['Delicias'],
        'Samuel Darío Maldonado': ['La Tendida', 'Boconó', 'Hernández'],
        'San Cristóbal': ['La Concordia', 'Pedro María Morantes', 'San Juan Bautista', 'San Sebastián', 'Francisco Romero Lobo'],
        'San Judas Tadeo': ['Umuquena'],
        'Seboruco': ['Seboruco'],
        'Simón Rodríguez': ['San Simón'],
        'Sucre': ['Queniquea', 'Eleazar López Contreras', 'San Pablo'],
        'Torbes': ['San Josecito'],
        'Uribante': ['Pregonero', 'Cárdenas', 'Potosí', 'Juan Pablo Peñaloza']
    };

    function updateParroquias() {
        const selectMunicipio = document.getElementById('municipio_id');
        const selectedOption = selectMunicipio.options[selectMunicipio.selectedIndex];
        const municipioNombre = selectedOption ? selectedOption.getAttribute('data-nombre') : null;
        const selectParroquia = document.getElementById('nombre');
        
        // Cargamos el valor "old" si existe (por error de validación), si no, el de la parroquia actual
        const currentValue = "{{ old('nombre', $parroquia->nombre) }}";
        
        selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';
        
        if (municipioNombre && parroquiasPorMunicipio[municipioNombre]) {
            parroquiasPorMunicipio[municipioNombre].forEach(parroquia => {
                const option = document.createElement('option');
                option.value = parroquia;
                option.textContent = parroquia;
                if (parroquia === currentValue) option.selected = true;
                selectParroquia.appendChild(option);
            });
        }
    }

    document.getElementById('municipio_id').addEventListener('change', updateParroquias);

    window.addEventListener('load', () => {
        updateParroquias();
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection