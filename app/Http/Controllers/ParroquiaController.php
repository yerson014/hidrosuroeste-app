<?php

namespace App\Http\Controllers;

use App\Models\Parroquia;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ParroquiaController extends Controller
{
    /**
     * Muestra la lista de parroquias con orden ascendente (1, 2, 3...).
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $parroquias = Parroquia::with('municipio')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', '%' . $buscar . '%');
            })
            // Cambiado de 'desc' a 'asc' para que el ID menor aparezca primero
            ->orderBy('parroquia_id', 'asc') 
            ->paginate(10)
            ->withQueryString();

        return view('territorial.parroquias.index', compact('parroquias', 'buscar'));
    }

    /**
     * Muestra el formulario para crear una nueva parroquia.
     */
    public function create()
    {
        $municipios = Municipio::orderBy('nombre', 'asc')->get();
        return view('territorial.parroquias.create', compact('municipios'));
    }

    /**
     * Almacena la parroquia en la base de datos.
     */
    public function store(Request $request)
    {
        // Validamos la unicidad basándonos en el nombre y el municipio_id
        $request->validate([
            'municipio_id' => 'required|exists:municipio,municipio_id',
            'nombre' => [
                'required',
                'string',
                'max:255',
                // Validación: único donde municipio_id coincida (ignora mayúsculas/minúsculas)
                Rule::unique('parroquia', 'nombre')->where(function ($query) use ($request) {
                    return $query->where('municipio_id', $request->municipio_id)
                                 ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                }),
            ],
        ], [
            'nombre.required' => 'El nombre de la parroquia es obligatorio.',
            'nombre.unique'   => 'Esta parroquia ya se encuentra registrada en el municipio seleccionado.',
            'municipio_id.required' => 'Debe seleccionar un municipio.'
        ]);

        try {
            Parroquia::create($request->all());
            return redirect()->route('parroquias.index')
                ->with('success', 'Parroquia registrada exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear parroquia: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar la parroquia.');
        }
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit($id)
    {
        $parroquia = Parroquia::findOrFail($id);
        $municipios = Municipio::orderBy('nombre', 'asc')->get();
        return view('territorial.parroquias.edit', compact('parroquia', 'municipios'));
    }

    /**
     * Actualiza la parroquia.
     */
    public function update(Request $request, $id)
    {
        $parroquia = Parroquia::findOrFail($id);

        $request->validate([
            'municipio_id' => 'required|exists:municipio,municipio_id',
            'nombre' => [
                'required',
                'string',
                'max:255',
                // Validamos duplicado ignorando el ID actual y filtrando por municipio_id
                Rule::unique('parroquia', 'nombre')
                    ->ignore($id, 'parroquia_id')
                    ->where(function ($query) use ($request) {
                        return $query->where('municipio_id', $request->municipio_id)
                                     ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                    }),
            ],
        ], [
            'nombre.required' => 'El nombre de la parroquia es obligatorio.',
            'nombre.unique'   => 'Ya existe otra parroquia con este nombre en el municipio seleccionado.',
        ]);

        try {
            $parroquia->update($request->all());
            return redirect()->route('parroquias.index')
                ->with('success', 'Parroquia actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar parroquia ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error al intentar actualizar los datos.');
        }
    }

    /**
     * Elimina la parroquia.
     */
    public function destroy($id)
    {
        try {
            $parroquia = Parroquia::findOrFail($id);

            // 1. Verificamos si hay una comunidad asociada a esta parroquia
            $comunidadAsociada = \App\Models\Comunidad::where('parroquia_id', $id)->first();

            if ($comunidadAsociada) {
                // 2. Si existe la comunidad, revisamos si también está enlazada a una comuna
                $comunaAsociada = \App\Models\Comuna::where('comunidad_id', $comunidadAsociada->comunidad_id)->first();

                if ($comunaAsociada) {
                    // Mensaje con el rastreo territorial completo
                    return back()->with('error_relacion', "La parroquia '{$parroquia->nombre}' no se puede eliminar porque está siendo referenciada por la comunidad '{$comunidadAsociada->nombre}', y esta comunidad está referenciada por la comuna '{$comunaAsociada->nombre}'.");
                }

                // Mensaje intermedio si la comunidad no se ha asignado a una comuna todavía
                return back()->with('error_relacion', "La parroquia '{$parroquia->nombre}' no se puede eliminar porque está siendo referenciada por la comunidad '{$comunidadAsociada->nombre}'.");
            }

            // 3. Validación de respaldo por si existiera una Comuna enlazada directo a la Parroquia
            $comunaDirecta = \App\Models\Comuna::where('parroquia_id', $id)->first();
            if ($comunaDirecta) {
                return back()->with('error_relacion', "La parroquia '{$parroquia->nombre}' no se puede eliminar porque está siendo referenciada por la comuna '{$comunaDirecta->nombre}'.");
            }

            $parroquia->delete();
            return redirect()->route('parroquias.index')
                ->with('success', 'Parroquia eliminada correctamente.');
                
        } catch (\Exception $e) {
            Log::error("Error al eliminar parroquia ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
}