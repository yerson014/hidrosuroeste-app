<?php

namespace App\Http\Controllers;

use App\Models\CentroAsociado;
use App\Models\Comunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CentroAsociadoController extends Controller
{
    /**
     * Muestra el listado de centros asociados con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $centros = CentroAsociado::with('comunidad')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('tipo', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('ubicacion', 'LIKE', '%' . $buscar . '%');
            })
            ->orderBy('centro_asociado_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('organizacion.centrosasociados.index', compact('centros', 'buscar'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $comunidades = Comunidad::orderBy('nombre', 'asc')->get();
        return view('organizacion.centrosasociados.create', compact('comunidades'));
    }

    /**
     * Guarda el centro asociado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'comunidad_id' => 'required|exists:comunidad,comunidad_id',
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('centro_asociado', 'nombre')->where(function ($query) use ($request) {
                    return $query->where('comunidad_id', $request->comunidad_id)
                                 ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                }),
            ],
            'tipo' => 'nullable|string|max:50',
            'ubicacion' => 'nullable|string|max:200',
        ], [
            'nombre.required' => 'El nombre del centro asociado es obligatorio.',
            'nombre.unique'   => 'Ya existe un centro con este nombre registrado en la comunidad seleccionada.',
        ]);

        try {
            CentroAsociado::create($request->all());
            return redirect()->route('centros-asociados.index')->with('success', 'Centro Asociado creado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear centro asociado: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar el centro asociado.')->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $centro = CentroAsociado::findOrFail($id);
        $comunidades = Comunidad::orderBy('nombre', 'asc')->get();
        return view('organizacion.centrosasociados.edit', compact('centro', 'comunidades'));
    }

    /**
     * Actualiza el centro asociado.
     */
    public function update(Request $request, $id)
    {
        $centro = CentroAsociado::findOrFail($id);

        $request->validate([
            'comunidad_id' => 'required|exists:comunidad,comunidad_id',
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('centro_asociado', 'nombre')
                    ->ignore($id, 'centro_asociado_id')
                    ->where(function ($query) use ($request) {
                        return $query->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)]);
                    }),
            ],
            'tipo' => 'nullable|string|max:50',
            'ubicacion' => 'nullable|string|max:200',
        ], [
            'nombre.required' => 'El nombre del centro asociado es obligatorio.',
            'nombre.unique'   => 'Ya existe un centro con este nombre registrado en el sistema.',
        ]);

        try {
            $centro->update($request->all());
            return redirect()->route('centros-asociados.index')->with('success', 'Centro Asociado actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar centro asociado ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Error al intentar actualizar los datos.');
        }
    }

    /**
     * Elimina el centro asociado verificando relaciones de dependencia.
     */
    public function destroy($id)
    {
        try {
            $centro = CentroAsociado::findOrFail($id);

            // Verificamos si este centro educativo está siendo referenciado por alguna mesa técnica del agua
            $mesaAsociada = \App\Models\MesaTecnica::where('centro_asociado_id', $id)->first();

            if ($mesaAsociada) {
                return back()->with('error_relacion', "El centro educativo '{$centro->nombre}' no se puede eliminar porque está siendo referenciado por la mesa técnica '{$mesaAsociada->nombre}'.");
            }

            $centro->delete();
            return redirect()->route('centros-asociados.index')->with('success', 'Centro Asociado eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar centro asociado ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el centro asociado.');
        }
    }
}