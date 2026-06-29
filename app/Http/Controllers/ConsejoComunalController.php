<?php

namespace App\Http\Controllers;

use App\Models\ConsejoComunal;
use App\Models\Comunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ConsejoComunalController extends Controller
{
    /**
     * Muestra el listado de consejos comunales con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $consejos = ConsejoComunal::with('comunidad')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('lider_nombre', 'LIKE', '%' . $buscar . '%');
            })
            ->orderBy('consejo_comunal_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('organizacion.consejoscomunales.index', compact('consejos', 'buscar'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $comunidades = Comunidad::orderBy('nombre', 'asc')->get();
        return view('organizacion.consejoscomunales.create', compact('comunidades'));
    }

    /**
     * Guarda el consejo comunal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'comunidad_id' => 'required|exists:comunidad,comunidad_id',
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('consejo_comunal', 'nombre')->where(function ($query) use ($request) {
                    return $query->where('comunidad_id', $request->comunidad_id)
                                 ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                }),
            ],
            'lider_nombre' => 'nullable|string|max:150',
            'lider_telefono' => 'nullable|string|max:20',
        ], [
            'nombre.required' => 'El nombre del consejo comunal es obligatorio.',
            'nombre.unique'   => 'Ya existe otro consejo comunal con este nombre en la comunidad seleccionada.',
        ]);

        try {
            ConsejoComunal::create($request->all());
            return redirect()->route('consejos-comunales.index')->with('success', 'Consejo Comunal registrado de forma exitosa.');
        } catch (\Exception $e) {
            Log::error("Error al crear consejo comunal: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar el consejo comunal.')->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $consejo = ConsejoComunal::findOrFail($id);
        $comunidades = Comunidad::orderBy('nombre', 'asc')->get();
        return view('organizacion.consejoscomunales.edit', compact('consejo', 'comunidades'));
    }

    /**
     * Actualiza el consejo comunal.
     */
    public function update(Request $request, $id)
    {
        $consejo = ConsejoComunal::findOrFail($id);

        $request->validate([
            'comunidad_id' => 'required|exists:comunidad,comunidad_id',
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('consejo_comunal', 'nombre')
                    ->ignore($id, 'consejo_comunal_id')
                    ->where(function ($query) use ($request) {
                        return $query->where('comunidad_id', $request->comunidad_id)
                                     ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                    }),
            ],
            'lider_nombre' => 'nullable|string|max:150',
            'lider_telefono' => 'nullable|string|max:20',
        ], [
            'nombre.required' => 'El nombre del consejo comunal es obligatorio.',
            'nombre.unique'   => 'Ya existe otro consejo comunal con este nombre en la comunidad seleccionada.',
        ]);

        try {
            $consejo->update($request->all());
            return redirect()->route('consejos-comunales.index')->with('success', 'Consejo Comunal actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar consejo comunal ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error al intentar actualizar los datos.');
        }
    }

    /**
     * Elimina el consejo comunal verificando dependencias con Mesas Técnicas.
     */
    public function destroy($id)
    {
        try {
            $consejo = ConsejoComunal::findOrFail($id);

            // Verificamos si este consejo comunal está asignado a una Mesa Técnica del Agua activa
            $mesaAsociada = \App\Models\MesaTecnica::where('consejo_comunal_id', $id)->first();

            if ($mesaAsociada) {
                return back()->with('error_relacion', "El consejo comunal '{$consejo->nombre}' no se puede eliminar porque está siendo referenciado por la mesa técnica '{$mesaAsociada->nombre}'.");
            }

            $consejo->delete();
            return redirect()->route('consejos-comunales.index')->with('success', 'Consejo Comunal eliminado.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar consejo comunal ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el consejo comunal.');
        }
    }
}