<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\ComunaController;
use App\Http\Controllers\ComunidadController;
use App\Http\Controllers\ConsejoComunalController;
use App\Http\Controllers\CentroAsociadoController;
use App\Http\Controllers\MesaTecnicaController;
use App\Http\Controllers\VoceroController;
use App\Http\Controllers\VoceroHistorialController;
use App\Http\Controllers\MesaHistorialController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Estas rutas devuelven exclusivamente JSON.
| URL base: http://127.0.0.1:8000/api/...
*/

Route::prefix('api')->group(function () {
    // Gestión Territorial
    Route::get('/municipios', [MunicipioController::class, 'index']);
    Route::get('/parroquias', [ParroquiaController::class, 'index']);
    Route::get('/comunas', [ComunaController::class, 'index']);
    Route::get('/comunidades', [ComunidadController::class, 'index']);

    // Organizaciones y Entidades
    Route::get('/consejos-comunales', [ConsejoComunalController::class, 'index']);
    Route::get('/centros-asociados', [CentroAsociadoController::class, 'index']);
    Route::get('/mesas-tecnicas', [MesaTecnicaController::class, 'index']);

    // Vocería e Historiales (Agregados)
    Route::get('/voceros', [VoceroController::class, 'index']);
    Route::get('/voceros-historial', [VoceroHistorialController::class, 'index']);
    Route::get('/mesas-historial', [MesaHistorialController::class, 'index']);

    // Gestión de Proyectos, Incidencias y Documentos (Agregados)
    Route::get('/proyectos', [ProyectoController::class, 'index']);
    Route::get('/incidencias', [IncidenciaController::class, 'index']);
    Route::get('/documentos', [DocumentoController::class, 'index']);
    
    // Auditoría y Usuarios
    Route::get('/bitacoras', [BitacoraController::class, 'index']);
    Route::get('/usuarios', [UsuarioController::class, 'index']);
});
