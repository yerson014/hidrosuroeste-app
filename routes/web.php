<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Auth\LoginController;
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

// Redirección inicial
Route::get('/', function () {
    // Forzamos a Laravel a revisar y ejecutar CUALQUIER migración pendiente (como mesa_tecnica)
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    } catch (\Exception $e) {
        return "Configurando tablas de la base de datos... Por favor refresca en 5 segundos. Error: " . $e->getMessage();
    }

    // FUERZA BRUTA: Limpiamos e insertamos al administrador con Hash nativo
    try {
        // En PostgreSQL, truncate requiere CASCADE si hay llaves foráneas apuntando
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE usuario RESTART IDENTITY CASCADE');

        // Insertamos usando Hash::make nativo (idéntico a como te funcionó local)
        \Illuminate\Support\Facades\DB::table('usuario')->insert([
            'nombre'           => 'Admin',
            'apellido'         => 'Hidrosuroeste',
            'cedula'           => '12345678',
            'correo'           => 'admin@hidrosuroeste.com',
            'password'         => \Illuminate\Support\Facades\Hash::make('admin123'), 
            'rol'              => 'Administrador',
            'fecha_nacimiento' => '1990-01-01',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    } catch (\Exception $e) {
        return "Error al crear el usuario: " . $e->getMessage();
    }

    // Redirige directo al login una vez creado/limpiado correctamente
    return redirect('/login');
});

// Rutas de Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/registro', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/registro', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas Protegidas (Solo usuarios logueados)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- GESTIÓN TERRITORIAL Y GEOGRÁFICO ---
    Route::resource('municipios', MunicipioController::class);
    Route::resource('parroquias', ParroquiaController::class);
    Route::resource('comunas', ComunaController::class);
    Route::resource('comunidades', ComunidadController::class);

    // --- GESTIÓN DE ORGANIZACIONES ---
    Route::resource('consejos-comunales', ConsejoComunalController::class)
    ->names('consejos-comunales');
    Route::resource('centros-asociados', CentroAsociadoController::class);
    Route::resource('mesas-tecnicas', MesaTecnicaController::class);

    // --- GESTIÓN DE VOCERÍA ---
    Route::resource('voceros', VoceroController::class);
    Route::resource('voceros-historial', VoceroHistorialController::class)
    ->names('voceros-historial');

    // --- GESTIÓN DE AUDITORÍA Y SEGUIMIENTO ---
    Route::resource('mesas-historial', MesaHistorialController::class)
    ->names('mesas-historial'); 
    Route::resource('proyectos', ProyectoController::class);
    // Ruta adicional para Reporte General antes del Resource para que no choque con 'show'
    Route::get('incidencias/reporte-general', [IncidenciaController::class, 'reporteGeneral'])->name('incidencias.reporte_general');
    Route::resource('incidencias', IncidenciaController::class);
    Route::resource('documentos', DocumentoController::class);
    // --- BITÁCORA (Corregido para incluir todas las rutas necesarias) ---
    // Usamos resource para que cree index, create, store, edit, update, destroy automáticamente
    // Pero forzamos el nombre 'bitacoras' para que coincida con tus archivos blade
    Route::resource('bitacoras', BitacoraController::class)->names([
        'index'   => 'bitacoras.index',
        'create'  => 'bitacoras.create',
        'store'   => 'bitacoras.store',
        'edit'    => 'bitacoras.edit',
        'update'  => 'bitacoras.update',
        'destroy' => 'bitacoras.destroy',
    ]);

    // RUTA PARA GESTIÓN DE USUARIOS (Administración)
    // El nombre 'usuarios.index' es el que Laravel busca cuando haces route('usuarios.index')
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/guardar', [UsuarioController::class, 'store'])->name('usuarios.store');

    // ESTA ES LA RUTA QUE TE FALTABA PARA QUE EL BOTÓN DE EDITAR FUNCIONE:
    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    // También es recomendable agregar la de actualizar (PUT) para cuando envíes el formulario de edición
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');

    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // RUTA PARA EDITAR MI PERFIL (El usuario logueado)
    Route::get('/perfil', [UsuarioController::class, 'editProfile'])->name('perfil.edit');
    Route::put('/perfil/actualizar', [UsuarioController::class, 'updateProfile'])->name('perfil.update');

    Route::get('mesas-tecnicas/{id}/pdf', [App\Http\Controllers\MesaTecnicaController::class, 'generarPdf'])->name('mesas-tecnicas.pdf');
});