<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ComunidadController extends Controller
{
    /**
     * Muestra el listado de comunidades con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $comunidades = Comunidad::with('parroquia.municipio')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('sector', 'LIKE', '%' . $buscar . '%');
            })
            ->orderBy('comunidad_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('territorial.comunidades.index', compact('comunidades', 'buscar'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $parroquias = Parroquia::orderBy('nombre', 'asc')->get();
        return view('territorial.comunidades.create', compact('parroquias'));
    }

    /**
     * Guarda la comunidad con validaciones de unicidad.
     */
    public function store(Request $request)
    {
        if ($request->has('sector') && $request->sector === '') {
            $request->merge(['sector' => null]);
        }

        $request->validate([
            'parroquia_id' => 'required|exists:parroquia,parroquia_id',
            'nombre' => [
                'required',
                'string',
                'max:255',
                // Validación: El nombre debe ser único dentro de la misma parroquia
                Rule::unique('comunidad', 'nombre')->where(function ($query) use ($request) {
                    return $query->where('parroquia_id', $request->parroquia_id)
                                 ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                }),
            ],
            'sector' => 'nullable|string|max:255',
            'habitantes' => 'nullable|integer|min:0',
            'familias' => 'nullable|integer|min:0',
            'hombres' => 'nullable|integer|min:0',
            'mujeres' => 'nullable|integer|min:0',
            'ninos' => 'nullable|integer|min:0',
        ], [
            'nombre.required' => 'El nombre de la comunidad es obligatorio.',
            'nombre.unique' => 'Esta comunidad ya se encuentra registrada en la parroquia seleccionada.',
            'parroquia_id.required' => 'Debe seleccionar una parroquia.'
        ]);

        try {
            // Convertir checkboxes a booleanos explícitos
            $data = $request->all();
            $data['usa_cisterna'] = $request->has('usa_cisterna');
            $data['agua_potable'] = $request->has('agua_potable');
            $data['zonas_silencio'] = $request->has('zonas_silencio');
            $data['tanques_grandes'] = $request->has('tanques_grandes');

            Comunidad::create($data);

            return redirect()->route('comunidades.index')
                ->with('success', 'Comunidad registrada exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear comunidad: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar la comunidad.')->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $comunidad = Comunidad::findOrFail($id);
        $parroquias = Parroquia::orderBy('nombre', 'asc')->get();
        return view('territorial.comunidades.edit', compact('comunidad', 'parroquias'));
    }

    /**
     * Actualiza la comunidad validando que no se duplique el nombre.
     */
    public function update(Request $request, $id)
    {
        $comunidad = Comunidad::findOrFail($id);
        
        $request->validate([
            'parroquia_id' => 'required|exists:parroquia,parroquia_id',
            'nombre' => [
                'required',
                'string',
                'max:255',
                // Ignora el ID actual para permitir guardar si no se cambió el nombre
                Rule::unique('comunidad', 'nombre')
                    ->ignore($id, 'comunidad_id')
                    ->where(function ($query) use ($request) {
                        return $query->where('parroquia_id', $request->parroquia_id)
                                     ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                    }),
            ],
            'sector' => 'nullable|string|max:255',
            'habitantes' => 'nullable|integer|min:0',
            'familias' => 'nullable|integer|min:0',
            'hombres' => 'nullable|integer|min:0',
            'mujeres' => 'nullable|integer|min:0',
            'ninos' => 'nullable|integer|min:0',
        ], [
            'nombre.required' => 'El nombre de la comunidad es obligatorio.',
            'nombre.unique' => 'Ya existe otra comunidad con este nombre en la parroquia seleccionada.',
        ]);

        try {
            $data = $request->all();
            $data['usa_cisterna'] = $request->has('usa_cisterna');
            $data['agua_potable'] = $request->has('agua_potable');
            $data['zonas_silencio'] = $request->has('zonas_silencio');
            $data['tanques_grandes'] = $request->has('tanques_grandes');

            $comunidad->update($data);
            return redirect()->route('comunidades.index')->with('success', 'Comunidad actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar comunidad ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error al intentar actualizar los datos.')->withInput();
        }
    }

    /**
     * Elimina la comunidad.
     */
    public function destroy($id)
    {
        try {
            $comunidad = Comunidad::findOrFail($id);

            // Verificamos si alguna comuna está referenciando esta comunidad
            $comunaAsociada = \App\Models\Comuna::where('comunidad_id', $id)->first();

            if ($comunaAsociada) {
                return back()->with('error_relacion', "La comunidad '{$comunidad->nombre}' no se puede eliminar porque está siendo referenciada por la comuna '{$comunaAsociada->nombre}'.");
            }

            $comunidad->delete();
            return redirect()->route('comunidades.index')->with('success', 'Comunidad eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar comunidad ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar la comunidad.');
        }
    }
}