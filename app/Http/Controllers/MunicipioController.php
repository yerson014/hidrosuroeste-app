<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MunicipioController extends Controller
{
    /**
     * Muestra la lista de municipios con soporte para búsqueda.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $municipios = Municipio::when($buscar, function ($query, $buscar) {
            return $query->where('nombre', 'LIKE', '%' . $buscar . '%');
        })
        ->orderBy('municipio_id', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('territorial.municipios.index', compact('municipios', 'buscar'));
    }

    /**
     * Muestra el formulario para crear un nuevo municipio.
     */
    public function create()
    {
        return view('territorial.municipios.create');
    }

    /**
     * Almacena un municipio recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        // Validamos la unicidad de forma insensible a mayúsculas/minúsculas
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                // Esta regla busca si existe el nombre ignorando mayúsculas/minúsculas
                Rule::unique('municipio', 'nombre')->where(function ($query) use ($request) {
                    return $query->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                }),
            ],
        ], [
            'nombre.required' => 'El nombre del municipio es obligatorio.',
            'nombre.unique'   => 'Este municipio ya existe en el sistema (el nombre no puede duplicarse ni en mayúsculas ni en minúsculas).',
        ]);

        try {
            // Guardamos el dato tal cual viene del formulario, sin forzar mayúsculas
            Municipio::create($request->all());
            return redirect()->route('municipios.index')
                ->with('success', 'Municipio creado exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear municipio: " . $e->getMessage());
            return back()->with('error', 'No se pudo crear el municipio por un error interno.');
        }
    }

    /**
     * Muestra el formulario para editar el municipio.
     */
    public function edit($id)
    {
        $municipio = Municipio::findOrFail($id);
        return view('territorial.municipios.edit', compact('municipio'));
    }

    /**
     * Actualiza el municipio en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $municipio = Municipio::findOrFail($id);

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                // Validamos duplicado ignorando el ID actual para que permita guardar si no se cambió el nombre
                Rule::unique('municipio', 'nombre')->ignore($id, 'municipio_id')->where(function ($query) use ($request) {
                    return $query->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                }),
            ],
        ], [
            'nombre.required' => 'El nombre del municipio es obligatorio.',
            'nombre.unique'   => 'Ya existe otro municipio registrado con este nombre.',
        ]);

        try {
            $municipio->update($request->all());
            return redirect()->route('municipios.index')
                ->with('success', 'Municipio actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar municipio ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error al intentar actualizar los datos.');
        }
    }

    /**
     * Elimina el municipio de la base de datos aplicando rastreo de dependencias.
     */
    public function destroy($id)
    {
        try {
            $municipio = Municipio::findOrFail($id);

            // 1. Verificamos si hay alguna parroquia asociada a este municipio
            $parroquiaAsociada = \App\Models\Parroquia::where('municipio_id', $id)->first();

            if ($parroquiaAsociada) {
                // 2. Si existe la parroquia, revisamos si tiene una comunidad enlazada
                $comunidadAsociada = \App\Models\Comunidad::where('parroquia_id', $parroquiaAsociada->parroquia_id)->first();

                if ($comunidadAsociada) {
                    // 3. Si existe la comunidad, revisamos si también llegó hasta una comuna
                    $comunaAsociada = \App\Models\Comuna::where('comunidad_id', $comunidadAsociada->comunidad_id)->first();

                    if ($comunaAsociada) {
                        // RASTREO COMPLETO: Municipio -> Parroquia -> Comunidad -> Comuna
                        return back()->with('error_relacion', "El municipio '{$municipio->nombre}' no se puede eliminar porque está siendo referenciado por la parroquia '{$parroquiaAsociada->nombre}', la cual tiene la comunidad '{$comunidadAsociada->nombre}', y esta comunidad está referenciada por la comuna '{$comunaAsociada->nombre}'.");
                    }

                    // RASTREO INTERMEDIO: Municipio -> Parroquia -> Comunidad
                    return back()->with('error_relacion', "El municipio '{$municipio->nombre}' no se puede eliminar porque está siendo referenciada por la parroquia '{$parroquiaAsociada->nombre}', la cual tiene la comunidad '{$comunidadAsociada->nombre}'.");
                }

                // RASTREO BÁSICO: Municipio -> Parroquia
                return back()->with('error_relacion', "El municipio '{$municipio->nombre}' no se puede eliminar porque está siendo referenciado por la parroquia '{$parroquiaAsociada->nombre}'.");
            }

            // 4. Validaciones de respaldo directo por consistencia interna en la base de datos
            $comunaDirecta = \App\Models\Comuna::where('parroquia_id', function($query) use ($id) {
                $query->select('parroquia_id')->from('parroquia')->where('municipio_id', $id)->limit(1);
            })->first();

            if ($comunaDirecta) {
                return back()->with('error_relacion', "El municipio '{$municipio->nombre}' no se puede eliminar porque existen comunas vinculadas indirectamente a sus parroquias.");
            }

            // Si pasa todas las validaciones, procedemos al borrado seguro
            $municipio->delete();

            return redirect()->route('municipios.index')
                ->with('success', 'Municipio eliminado correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al eliminar municipio ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
}