<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BitacoraController extends Controller
{
    /**
     * Listado de bitácoras con búsqueda por acción, entidad o descripción.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $bitacoras = Bitacora::with('usuario')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('accion', 'ILIKE', '%' . $buscar . '%')
                             ->orWhere('entidad', 'ILIKE', '%' . $buscar . '%')
                             ->orWhere('descripcion', 'ILIKE', '%' . $buscar . '%');
            })
            ->orderBy('bitacora_id', 'desc') // Lo más reciente primero
            ->paginate(10)
            ->withQueryString();

        return view('configuracion.bitacoras.index', compact('bitacoras', 'buscar'));
    }

    /**
     * Formulario de creación (Registro manual de actividad).
     */
    public function create()
    {
        return view('configuracion.bitacoras.create');
    }

    /**
     * Guarda un registro en la bitácora.
     */
    public function store(Request $request)
    {
        $request->validate([
            'accion'      => 'required|string|max:30',
            'entidad'     => 'required|string|max:50',
            'descripcion' => 'required|string',
        ]);

        try {
            Bitacora::create([
                'usuario_id'  => Auth::id(),
                'accion'      => $request->accion,
                'entidad'     => $request->entidad,
                'descripcion' => $request->descripcion,
                'fecha'       => now(),
                'ip'          => $request->ip()
            ]);

            return redirect()->route('bitacoras.index')
                ->with('success', 'Registro de bitácora creado exitosamente.');

        } catch (\Exception $e) {
            Log::error("Error al crear bitácora: " . $e->getMessage());
            return back()->with('error', 'Error técnico: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $bitacora = Bitacora::findOrFail($id);
        return view('configuracion.bitacoras.edit', compact('bitacora'));
    }

    /**
     * Actualiza el registro.
     */
    public function update(Request $request, $id)
    {
        $bitacora = Bitacora::findOrFail($id);
        
        $request->validate([
            'accion'      => 'required|string|max:30',
            'entidad'     => 'required|string|max:50',
            'descripcion' => 'required|string',
        ]);

        try {
            $bitacora->update($request->all());
            return redirect()->route('bitacoras.index')->with('success', 'Registro actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Elimina el registro.
     */
    public function destroy($id)
    {
        try {
            $bitacora = Bitacora::findOrFail($id);
            $bitacora->delete();
            return redirect()->route('bitacoras.index')->with('success', 'Registro eliminado de la bitácora.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
}