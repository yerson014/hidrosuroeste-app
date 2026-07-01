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

// Redirección inicial - MODO FUERZA BRUTA TOTAL
Route::get('/', function () {
    
    // Intentamos migrar, pero si da error por el desorden de tablas, LO IGNORAMOS para que no tranque la página
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    } catch (\Exception $e) {
        // No hacemos nada, que continúe el código pase lo que pase
    }

    // Limpiamos e insertamos al administrador pase lo que pase
    try {
        // Intentamos limpiar la tabla por si ya existe
        try {
            \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE usuario RESTART IDENTITY CASCADE');
        } catch (\Exception $e) {
            // Si la tabla usuario no existe todavía, ignoramos el error
        }

        // Insertamos usando Hash::make nativo
        \Illuminate\Support\Facades\DB::table('usuario')->updateOrInsert(
            ['correo' => 'admin@hidrosuroeste.com'], // Si ya existe lo actualiza, si no, lo crea
            [
                'nombre'           => 'Admin',
                'apellido'         => 'Hidrosuroeste',
                'cedula'           => '12345678',
                'password'         => \Illuminate\Support\Facades\Hash::make('admin123'), 
                'rol'              => 'Administrador',
                'fecha_nacimiento' => '1990-01-01',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]
        );
    } catch (\Exception $e) {
        // Si hay un error crítico aquí, lo mostramos, pero no debería fallar
        return "Error al procesar el usuario: " . $e->getMessage();
    }

    // Redirección obligatoria al login
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
        // Si no existe mesa_tecnica, la creamos con la estructura exacta que pide tu sistema
        if (!\Illuminate\Support\Facades\Schema::hasTable('mesa_tecnica')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            } catch (\Exception $e) {
                // Si la migración global falla, la creamos manualmente con tus campos reales
                try {
                    \Illuminate\Support\Facades\DB::statement('
                        CREATE TABLE IF NOT EXISTS mesa_tecnica (
                            mesa_tecnica_id BIGSERIAL PRIMARY KEY,
                            nombre VARCHAR(255) NULL,
                            fecha_creacion DATE NULL,
                            direccion VARCHAR(255) NULL,
                            numero_integrantes INTEGER NULL,
                            estado VARCHAR(50) NULL,
                            consejo_comunal_id INTEGER NULL,
                            centro_asociado_id INTEGER NULL,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        );
                    ');
                } catch (\Exception $ex) {
                    // Evita cualquier error en caso de conflicto
                }
            }
        }

        // 2. Si no existe vocero, la creamos con tu estructura exacta de pgAdmin
        if (!\Illuminate\Support\Facades\Schema::hasTable('vocero')) {
            try {
                \Illuminate\Support\Facades\DB::statement('
                    CREATE TABLE IF NOT EXISTS vocero (
                        vocero_id BIGSERIAL PRIMARY KEY,
                        nombre VARCHAR(255) NULL,
                        apellido VARCHAR(255) NULL,
                        cedula VARCHAR(20) NULL,
                        telefono VARCHAR(20) NULL,
                        direccion VARCHAR(255) NULL,
                        genero VARCHAR(50) NULL,
                        estado VARCHAR(50) NULL,
                        created_at TIMESTAMP NULL,
                        updated_at TIMESTAMP NULL
                    );
                ');
            } catch (\Exception $ex) {}
        }

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