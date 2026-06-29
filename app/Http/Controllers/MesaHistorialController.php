<?php

namespace App\Http\Controllers;

use App\Models\MesaHistorial;
use App\Models\MesaTecnica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MesaHistorialController extends Controller
{
    /**
     * Display a listing of the history.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $historial = MesaHistorial::with(['mesaTecnica', 'usuario'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('descripcion', 'LIKE', '%' . $buscar . '%')
                             ->orWhereHas('mesaTecnica', function($q) use ($buscar) {
                                 $q->where('nombre', 'LIKE', '%' . $buscar . '%');
                             });
            })
            ->orderBy('mesa_historial_id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('organizacion.mesashistorial.index', compact('historial', 'buscar'));
    }

    /**
     * Show the form for creating a new history entry.
     */
    public function create()
    {
        $mesas = MesaTecnica::orderBy('nombre', 'asc')->get();
        return view('organizacion.mesashistorial.create', compact('mesas'));
    }

    /**
     * Store a newly created history entry in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mesa_tecnica_id' => [
                'required',
                'exists:mesa_tecnica,mesa_tecnica_id',
                // Validación: No permite que la mesa ya tenga un registro en el historial
                Rule::unique('mesa_historial', 'mesa_tecnica_id'),
            ],
            'descripcion' => 'required|string',
            'fecha' => 'nullable|date',
        ], [
            'mesa_tecnica_id.required' => 'Debe seleccionar una mesa técnica.',
            'mesa_tecnica_id.unique'   => 'Esta mesa técnica ya tiene un registro en el historial y no puede duplicarse.',
            'descripcion.required' => 'La descripción es obligatoria.'
        ]);

        try {
            $userId = Auth::check() ? Auth::user()->getAuthIdentifier() : null;

            if (!$userId) {
                return back()->with('error', 'Debes estar autenticado para realizar esta acción.')->withInput();
            }

            MesaHistorial::create([
                'mesa_tecnica_id' => $request->mesa_tecnica_id,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha ?? now(),
                'usuario_id' => $userId, 
            ]);

            return redirect()->route('mesas-historial.index')
                ->with('success', 'Evento registrado en el historial exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear historial: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar el historial.')->withInput();
        }
    }

    /**
     * Show the form for editing the specified history entry.
     */
    public function edit($id)
    {
        $registro = MesaHistorial::findOrFail($id);
        $mesas = MesaTecnica::orderBy('nombre', 'asc')->get();
        return view('organizacion.mesashistorial.edit', compact('registro', 'mesas'));
    }

    /**
     * Update the specified history entry in storage.
     */
    public function update(Request $request, $id)
    {
        $registro = MesaHistorial::findOrFail($id);
        
        $request->validate([
            'mesa_tecnica_id' => [
                'required',
                'exists:mesa_tecnica,mesa_tecnica_id',
                // Ignora el ID actual para permitir editar otros campos sin error de duplicado
                Rule::unique('mesa_historial', 'mesa_tecnica_id')->ignore($id, 'mesa_historial_id'),
            ],
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
        ], [
            'mesa_tecnica_id.unique' => 'Ya existe otra mesa técnica con este nombre en el historial.',
            'descripcion.required' => 'La descripción es obligatoria.',
        ]);

        try {
            $registro->update($request->all());
            return redirect()->route('mesas-historial.index')->with('success', 'Registro de historial actualizado.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar historial: " . $e->getMessage());
            return back()->with('error', 'Error al actualizar.');
        }
    }

    /**
     * Remove the specified history entry from storage.
     */
    public function destroy($id)
    {
        try {
            $registro = MesaHistorial::findOrFail($id);
            $registro->delete();
            return redirect()->route('mesas-historial.index')->with('success', 'Registro eliminado del historial.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar historial ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
}