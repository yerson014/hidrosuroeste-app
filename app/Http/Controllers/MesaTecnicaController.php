<?php

namespace App\Http\Controllers;

use App\Models\MesaTecnica;
use App\Models\ConsejoComunal;
use App\Models\CentroAsociado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class MesaTecnicaController extends Controller
{
    /**
     * Muestra el listado de mesas técnicas con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $mesas = MesaTecnica::with(['consejoComunal', 'centroAsociado'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('estado', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('direccion', 'LIKE', '%' . $buscar . '%');
            })
            ->orderBy('mesa_tecnica_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('organizacion.mesastecnicas.index', compact('mesas', 'buscar'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $consejos = ConsejoComunal::orderBy('nombre', 'asc')->get();
        $centros = CentroAsociado::orderBy('nombre', 'asc')->get();
        return view('organizacion.mesastecnicas.create', compact('consejos', 'centros'));
    }

    /**
     * Guarda la mesa técnica.
     */
    public function store(Request $request)
    {
        $request->validate([
            'consejo_comunal_id' => 'required|exists:consejo_comunal,consejo_comunal_id',
            'nombre' => [
                'required',
                'string',
                'max:150',
                // CAMBIO A UNICIDAD GLOBAL: No permite el mismo nombre en toda la tabla
                Rule::unique('mesa_tecnica', 'nombre')->where(function ($query) use ($request) {
                    return $query->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                }),
            ],
            'centro_asociado_id' => 'nullable|exists:centro_asociado,centro_asociado_id',
            'fecha_creacion' => 'nullable|date|before_or_equal:today', // Validación de fecha real hasta hoy 2026
            'direccion' => 'nullable|string|max:200',
            'numero_integrantes' => 'nullable|integer|min:0',
            'estado' => 'required|in:activa,inactiva',
        ], [
            'nombre.required' => 'El nombre de la mesa técnica es obligatorio.',
            'nombre.unique'   => 'Este nombre de mesa técnica ya existe en el sistema. Use un nombre distintivo.',
            'consejo_comunal_id.required' => 'Debe seleccionar un consejo comunal.',
            'estado.required' => 'Debe seleccionar un estado operativo.',
            'fecha_creacion.date' => 'Por favor, ingresa una fecha de constitución válida.',
            'fecha_creacion.before_or_equal' => 'La fecha de constitución no puede ser una fecha futura.'
        ]);

        try {
            MesaTecnica::create($request->all());

            return redirect()->route('mesas-tecnicas.index')
                ->with('success', 'Mesa Técnica registrada exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear mesa técnica: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar la mesa técnica.')->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $mesa = MesaTecnica::findOrFail($id);
        $consejos = ConsejoComunal::orderBy('nombre', 'asc')->get();
        $centros = CentroAsociado::orderBy('nombre', 'asc')->get();
        return view('organizacion.mesastecnicas.edit', compact('mesa', 'consejos', 'centros'));
    }

    /**
     * Actualiza la mesa técnica.
     */
    public function update(Request $request, $id)
    {
        $mesa = MesaTecnica::findOrFail($id);
        
        $request->validate([
            'consejo_comunal_id' => 'required|exists:consejo_comunal,consejo_comunal_id',
            'nombre' => [
                'required',
                'string',
                'max:150',
                // Ignora el registro actual para permitir guardar sin cambiar el nombre
                Rule::unique('mesa_tecnica', 'nombre')
                    ->ignore($id, 'mesa_tecnica_id')
                    ->where(function ($query) use ($request) {
                        return $query->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                    }),
            ],
            'fecha_creacion' => 'nullable|date|before_or_equal:today', // Validación de fecha real hasta hoy 2026
            'estado' => 'required|in:activa,inactiva',
        ], [
            'nombre.required' => 'El nombre de la mesa técnica es obligatorio.',
            'nombre.unique'   => 'Ya existe otra mesa técnica registrada con este nombre.',
            'fecha_creacion.date' => 'Por favor, ingresa una fecha de constitución válida.',
            'fecha_creacion.before_or_equal' => 'La fecha de constitución no puede ser una fecha futura.'
        ]);

        try {
            $mesa->update($request->all());
            return redirect()->route('mesas-tecnicas.index')->with('success', 'Mesa Técnica actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar mesa técnica ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error al intentar actualizar los datos.');
        }
    }

    /**
     * Resto de métodos (destroy, generarPdf, descargarPDF) permanecen igual...
     */
    public function destroy($id)
    {
        try {
            $mesa = MesaTecnica::findOrFail($id);

            // Buscar si existen dependencias en Proyectos e Incidencias simultáneamente
            $proyectoAsociado = \App\Models\Proyecto::where('mesa_tecnica_id', $id)->first();
            $incidenciaAsociada = \App\Models\Incidencia::where('mesa_tecnica_id', $id)->first();

            // Si se detecta cualquier tipo de relación, estructuramos la alerta dinámica
            if ($proyectoAsociado || $incidenciaAsociada) {
                
                $mensaje = "La mesa técnica '{$mesa->nombre}' no se puede eliminar porque está siendo referenciada por ";

                // Plan de contingencia por si los campos descriptivos vienen vacíos de la Base de Datos
                $nombreProyecto = !empty($proyectoAsociado->nombre) ? $proyectoAsociado->nombre : ($proyectoAsociado->proyecto_id ?? 'Código #' . $proyectoAsociado->id ?? 'N/A');
                
                // Intentar capturar 'asunto', si no 'titulo', si no 'descripcion', o finalmente su ID
                $nombreIncidencia = !empty($incidenciaAsociada->asunto) ? $incidenciaAsociada->asunto : ($incidenciaAsociada->titulo ?? $incidenciaAsociada->descripcion ?? 'Código #' . $incidenciaAsociada->incidencia_id ?? 'N/A');

                if ($proyectoAsociado && $incidenciaAsociada) {
                    // Si está enlazada tanto a un proyecto como a una incidencia
                    $mensaje .= "el proyecto '{$nombreProyecto}' y la incidencia '{$nombreIncidencia}'.";
                } elseif ($proyectoAsociado) {
                    // Si únicamente está enlazada a un proyecto
                    $mensaje .= "el proyecto '{$nombreProyecto}'.";
                } else {
                    // Si únicamente está enlazada a una incidencia
                    $mensaje .= "la incidencia '{$nombreIncidencia}'.";
                }

                return back()->with('error_relacion', $mensaje);
            }

            // Si no tiene dependencias activas, se elimina con éxito
            $mesa->delete();
            return redirect()->route('mesas-tecnicas.index')->with('success', 'Mesa Técnica eliminada.');
            
        } catch (\Exception $e) {
            Log::error("Error al eliminar mesa técnica ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar la mesa técnica.');
        }
    }

    public function generarPdf($id)
    {
        $mesa = MesaTecnica::with(['consejoComunal', 'centroAsociado'])->findOrFail($id);
        return view('organizacion.mesastecnicas.registro', compact('mesa'));
    }

    public function descargarPDF($id)
    {
        $mesa = MesaTecnica::with(['consejoComunal', 'centroAsociado'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('organizacion.mesastecnicas.registro', compact('mesa'));
        return $pdf->download('MTA-' . $mesa->mesa_tecnica_id . '.pdf');
    }
}