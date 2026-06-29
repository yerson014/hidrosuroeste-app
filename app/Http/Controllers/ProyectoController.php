<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\MesaTecnica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProyectoController extends Controller
{
    /**
     * Muestra el listado de proyectos con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $proyectos = Proyecto::with(['mesaTecnica'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('titulo', 'ILIKE', '%' . $buscar . '%')
                             ->orWhere('ubicacion', 'ILIKE', '%' . $buscar . '%')
                             ->orWhere('estado', 'ILIKE', '%' . $buscar . '%');
            })
            ->orderBy('proyecto_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('gestion.proyectos.index', compact('proyectos', 'buscar'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $mesas = MesaTecnica::orderBy('nombre', 'asc')->get();
        return view('gestion.proyectos.create', compact('mesas'));
    }

    /**
     * Guarda el proyecto con validación manual contra duplicados (Case-Insensitive).
     */
    public function store(Request $request)
    {
        // 1. Verificación manual de duplicados ignorando mayúsculas/minúsculas
        $existe = Proyecto::whereRaw('LOWER(titulo) = ?', [strtolower($request->titulo)])->exists();

        if ($existe) {
            return back()
                ->withErrors(['titulo' => 'Ya existe un proyecto registrado con este nombre. No se permiten duplicados aunque cambie el uso de mayúsculas.'])
                ->withInput();
        }

        // 2. Validaciones de formato y existencia de llaves foráneas
        $request->validate([
            'mesa_tecnica_id' => 'required|exists:mesa_tecnica,mesa_tecnica_id',
            'titulo'          => 'required|string|max:150',
            'descripcion'     => 'nullable|string',
            'ubicacion'       => 'nullable|string|max:200',
            'fecha'           => 'nullable|date|before_or_equal:today', // Validación de fecha real hasta hoy 2026
            'estado'          => 'required|in:propuesto,aprobado,ejecutado',
        ], [
            'titulo.required' => 'El título del proyecto es obligatorio.',
            'mesa_tecnica_id.required' => 'Debe seleccionar una mesa técnica responsable.',
            'fecha.date' => 'El formato de la fecha no es válido.',
            'fecha.before_or_equal' => 'La fecha de registro no puede ser una fecha futura.',
        ]);

        try {
            $data = $request->all();
            $data['usuario_id'] = Auth::id(); 

            // Se crea el registro preservando las mayúsculas originales del usuario
            Proyecto::create($data);

            return redirect()->route('proyectos.index')
                ->with('success', 'Proyecto registrado exitosamente.');

        } catch (\Exception $e) {
            Log::error("Error al crear proyecto: " . $e->getMessage());
            return back()->with('error', 'Error técnico al registrar el proyecto.')->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $mesas = MesaTecnica::orderBy('nombre', 'asc')->get();
        return view('gestion.proyectos.edit', compact('proyecto', 'mesas'));
    }

    /**
     * Actualiza el proyecto validando duplicados excepto para el registro actual.
     */
    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        // Verificación de duplicados para el nuevo título (excluyendo el proyecto actual)
        $existe = Proyecto::whereRaw('LOWER(titulo) = ?', [strtolower($request->titulo)])
            ->where('proyecto_id', '!=', $id)
            ->exists();

        if ($existe) {
            return back()
                ->withErrors(['titulo' => 'No se puede actualizar: el nombre ya pertenece a otro proyecto registrado.'])
                ->withInput();
        }
        
        $request->validate([
            'mesa_tecnica_id' => 'required|exists:mesa_tecnica,mesa_tecnica_id',
            'titulo'          => 'required|string|max:150',
            'estado'          => 'required|in:propuesto,aprobado,ejecutado',
            'ubicacion'       => 'nullable|string|max:200',
            'fecha'           => 'nullable|date|before_or_equal:today', // Validación de fecha real hasta hoy 2026
            'descripcion'     => 'nullable|string',
        ], [
            'fecha.date' => 'El formato de la fecha no es válido.',
            'fecha.before_or_equal' => 'La fecha de registro no puede ser una fecha futura.',
        ]);

        try {
            $proyecto->update($request->all());
            return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar proyecto: " . $e->getMessage());
            return back()->with('error', 'Error al intentar actualizar los datos del proyecto.');
        }
    }

    /**
     * Elimina el proyecto.
     */
    public function destroy($id)
    {
        try {
            $proyecto = Proyecto::findOrFail($id);
            $proyecto->delete();
            return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar proyecto ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el registro debido a un error de base de datos.');
        }
    }
}