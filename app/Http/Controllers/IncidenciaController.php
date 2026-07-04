<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\MesaTecnica;
use App\Models\Comunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IncidenciaController extends Controller
{
    /**
     * Muestra el listado de incidencias con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $incidencias = Incidencia::with(['mesaTecnica', 'comunidad'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('titulo', 'ILIKE', '%' . $buscar . '%')
                             ->orWhere('tipo', 'ILIKE', '%' . $buscar . '%')
                             ->orWhere('prioridad', 'ILIKE', '%' . $buscar . '%')
                             ->orWhere('estado', 'ILIKE', '%' . $buscar . '%');
            })
            ->orderBy('incidencia_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('gestion.incidencias.index', compact('incidencias', 'buscar'));
    }

    /**
     * Genera el reporte general filtrado por fechas.
     */
    public function reporteGeneral(Request $request)
    {
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_fin = $request->get('fecha_fin');

        $query = Incidencia::with(['mesaTecnica', 'comunidad']);

        if ($fecha_inicio && $fecha_fin) {
            $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        }

        $incidencias = $query->orderBy('fecha', 'desc')->get();

        return view('gestion.incidencias.reporte_general', compact('incidencias', 'fecha_inicio', 'fecha_fin'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $mesas = MesaTecnica::orderBy('nombre', 'asc')->get();
        $comunidades = Comunidad::orderBy('nombre', 'asc')->get();
        return view('gestion.incidencias.create', compact('mesas', 'comunidades'));
    }

    /**
     * Guarda la incidencia con validación de unicidad estricta y fecha válida.
     */
    public function store(Request $request)
    {
        // Validación manual de duplicidad (Case-Insensitive)
        $existe = Incidencia::whereRaw('LOWER(titulo) = ?', [strtolower($request->titulo)])->exists();

        if ($existe) {
            return back()
                ->withErrors(['titulo' => 'Ya existe una incidencia registrada con este título. No se permiten duplicados.'])
                ->withInput();
        }

        $request->validate([
            'mesa_tecnica_id' => 'required|exists:mesa_tecnica,mesa_tecnica_id',
            'comunidad_id'    => 'required|exists:comunidad,comunidad_id',
            'titulo'          => 'required|string|max:150',
            'tipo'            => 'nullable|string|max:100',
            'descripcion'     => 'nullable|string',
            'prioridad'       => 'nullable|string|max:30',
            'estado'          => 'required|in:registrada,atendida,resuelta,suspendida', // <-- 'suspendida' agregado aquí
            'fecha'           => 'nullable|date|before_or_equal:today', 
        ], [
            'titulo.required' => 'El título de la incidencia es obligatorio.',
            'mesa_tecnica_id.required' => 'Debe seleccionar una mesa técnica.',
            'comunidad_id.required' => 'Debe seleccionar una comunidad.',
            'fecha.date' => 'Por favor, ingresa una fecha válida.', 
            'fecha.before_or_equal' => 'No puedes ingresar fechas futuras. Por favor, selecciona una fecha válida.', 
        ]);

        try {
            $data = $request->all();
            $data['usuario_id'] = Auth::id(); 

            Incidencia::create($data);

            return redirect()->route('incidencias.index')
                ->with('success', 'Incidencia registrada exitosamente.');

        } catch (\Exception $e) {
            Log::error("Error al crear incidencia: " . $e->getMessage());
            return back()->with('error', 'Error técnico: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $mesas = MesaTecnica::orderBy('nombre', 'asc')->get();
        $comunidades = Comunidad::orderBy('nombre', 'asc')->get();
        return view('gestion.incidencias.edit', compact('incidencia', 'mesas', 'comunidades'));
    }

    /**
     * Actualiza la incidencia validando fecha y unicidad de título.
     */
    public function update(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);

        // Validación manual de duplicidad para actualización (Case-Insensitive)
        $existe = Incidencia::whereRaw('LOWER(titulo) = ?', [strtolower($request->titulo)])
            ->where('incidencia_id', '!=', $id)
            ->exists();

        if ($existe) {
            return back()
                ->withErrors(['titulo' => 'No se puede actualizar: ya existe otra incidencia registrada con este título.'])
                ->withInput();
        }
        
        $request->validate([
            'mesa_tecnica_id' => 'required|exists:mesa_tecnica,mesa_tecnica_id',
            'comunidad_id'    => 'required|exists:comunidad,comunidad_id',
            'titulo'          => 'required|string|max:150',
            'estado'          => 'required|in:registrada,atendida,resuelta,suspendida', // <-- 'suspendida' agregado aquí
            'tipo'            => 'nullable|string|max:100',
            'prioridad'       => 'nullable|string|max:30',
            'fecha'           => 'nullable|date|before_or_equal:today', 
            'descripcion'     => 'nullable|string',
        ], [
            'fecha.date' => 'Por favor, ingresa una fecha válida.', 
            'fecha.before_or_equal' => 'No puedes ingresar fechas futuras. Por favor, selecciona una fecha válida.', 
        ]);

        try {
            $incidencia->update($request->all());
            return redirect()->route('incidencias.index')->with('success', 'Incidencia actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar incidencia: " . $e->getMessage());
            return back()->with('error', 'Error al actualizar la incidencia: ' . $e->getMessage());
        }
    }

    /**
     * Elimina la incidencia.
     */
    public function destroy($id)
    {
        try {
            $incidencia = Incidencia::findOrFail($id);
            $incidencia->delete();
            return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar incidencia ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar la incidencia.');
        }
    }

    /**
     * Genera la vista del reporte para imprimir/guardar como PDF.
     */
    public function show($id)
    {
        $incidencia = Incidencia::with(['mesaTecnica', 'comunidad'])->findOrFail($id);
        return view('gestion.incidencias.reporte', compact('incidencia'));
    }
}