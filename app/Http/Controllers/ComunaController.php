<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use App\Models\Parroquia;
use App\Models\Comunidad; // Importamos el modelo Comunidad
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ComunaController extends Controller
{
    /**
     * Muestra el listado de comunas con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        // Cargamos también la relación 'comunidad'
        $comunas = Comuna::with(['parroquia.municipio', 'comunidad'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', '%' . $buscar . '%');
            })
            ->orderBy('comuna_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('territorial.comunas.index', compact('comunas', 'buscar'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $parroquias = Parroquia::orderBy('nombre', 'asc')->get();
        // Buscamos las comunidades para el select del formulario
        $comunidades = Comunidad::orderBy('nombre', 'asc')->get();
        
        return view('territorial.comunas.create', compact('parroquias', 'comunidades'));
    }

    /**
     * Guarda la comuna.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parroquia_id' => 'required|exists:parroquia,parroquia_id',
            'comunidad_id' => 'nullable|exists:comunidad,comunidad_id', // Validación de la nueva FK
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('comuna', 'nombre')
                    ->where(function ($query) use ($request) {
                        return $query->where('parroquia_id', $request->parroquia_id)
                                     ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                    }),
            ],
            'codigo_comuna' => 'nullable|string|max:50|unique:comuna,codigo_comuna'
        ], [
            'nombre.required' => 'El nombre de la comuna es obligatorio.',
            'nombre.unique'   => 'Ya existe una comuna con este nombre en la parroquia seleccionada.',
            'codigo_comuna.unique' => 'El código de comuna ya está en uso.',
            'comunidad_id.exists' => 'La comunidad seleccionada no es válida.'
        ]);

        try {
            Comuna::create($request->all());
            return redirect()->route('comunas.index')
                ->with('success', 'Comuna registrada exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear comuna: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar la comuna.')->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $comuna = Comuna::findOrFail($id);
        $parroquias = Parroquia::orderBy('nombre', 'asc')->get();
        // Buscamos las comunidades para la edición
        $comunidades = Comunidad::orderBy('nombre', 'asc')->get();
        
        return view('territorial.comunas.edit', compact('comuna', 'parroquias', 'comunidades'));
    }

    /**
     * Actualiza la comuna.
     */
    public function update(Request $request, $id)
    {
        $comuna = Comuna::findOrFail($id);
        
        $request->validate([
            'parroquia_id' => 'required|exists:parroquia,parroquia_id',
            'comunidad_id' => 'nullable|exists:comunidad,comunidad_id', // Validación en actualización
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('comuna', 'nombre')
                    ->ignore($id, 'comuna_id')
                    ->where(function ($query) use ($request) {
                        return $query->where('parroquia_id', $request->parroquia_id)
                                     ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                    }),
            ],
            'codigo_comuna' => 'nullable|string|max:50|unique:comuna,codigo_comuna,' . $id . ',comuna_id'
        ], [
            'nombre.required' => 'El nombre de la comuna es obligatorio.',
            'nombre.unique'   => 'Ya existe otra comuna con este nombre en la parroquia seleccionada.',
            'codigo_comuna.unique' => 'El código de comuna ya está en uso.',
            'comunidad_id.exists' => 'La comunidad seleccionada no es válida.'
        ]);

        try {
            $comuna->update($request->all());
            return redirect()->route('comunas.index')
                ->with('success', 'Comuna actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar comuna ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error al intentar actualizar los datos.');
        }
    }

    /**
     * Elimina la comuna.
     */
    public function destroy($id)
    {
        try {
            $comuna = Comuna::findOrFail($id);
            $comuna->delete();
            return redirect()->route('comunas.index')
                ->with('success', 'Comuna eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar comuna ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se puede eliminar la comuna porque tiene registros asociados.');
        }
    }
}