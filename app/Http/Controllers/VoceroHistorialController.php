<?php

namespace App\Http\Controllers;

use App\Models\VoceroHistorial;
use App\Models\Vocero;
use App\Models\MesaTecnica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VoceroHistorialController extends Controller
{
    /**
     * Display a listing of the history.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $historial = VoceroHistorial::with(['vocero', 'mesaTecnica', 'usuario'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('motivo_salida', 'LIKE', '%' . $buscar . '%')
                             ->orWhereHas('vocero', function($q) use ($buscar) {
                                 $q->where('nombre', 'LIKE', '%' . $buscar . '%')
                                   ->orWhere('apellido', 'LIKE', '%' . $buscar . '%');
                             })
                             ->orWhereHas('mesaTecnica', function($q) use ($buscar) {
                                 $q->where('nombre', 'LIKE', '%' . $buscar . '%');
                             });
            })
            ->orderBy('vocero_historial_id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('organizacion.voceroshistorial.index', compact('historial', 'buscar'));
    }

    /**
     * Show the form for creating a new history entry.
     */
    public function create()
    {
        $voceros = Vocero::orderBy('nombre', 'asc')->get();
        $mesas = MesaTecnica::orderBy('nombre', 'asc')->get();
        return view('organizacion.voceroshistorial.create', compact('voceros', 'mesas'));
    }

    /**
     * Store a newly created history entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vocero_id' => [
                'required',
                'exists:vocero,vocero_id',
                // Esta regla busca que el vocero_id no se repita en la tabla de historial
                Rule::unique('vocero_historial', 'vocero_id'),
            ],
            'mesa_tecnica_id' => 'required|exists:mesa_tecnica,mesa_tecnica_id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'motivo_salida' => 'nullable|string',
        ], [
            'vocero_id.required' => 'Debe seleccionar un vocero.',
            'vocero_id.unique'   => 'Este vocero ya se encuentra registrado en el historial. No se permiten duplicados.',
            'mesa_tecnica_id.required' => 'Debe seleccionar una mesa técnica.',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la de inicio.'
        ]);

        try {
            $userId = Auth::check() ? Auth::user()->getAuthIdentifier() : null;

            if (!$userId) {
                return back()->with('error', 'Debes estar autenticado para realizar esta acción.')->withInput();
            }

            VoceroHistorial::create([
                'vocero_id' => $request->vocero_id,
                'mesa_tecnica_id' => $request->mesa_tecnica_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'motivo_salida' => $request->motivo_salida,
                'usuario_id' => $userId,
            ]);

            return redirect()->route('voceros-historial.index')
                ->with('success', 'Historial del vocero registrado exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear historial de vocero: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar el historial.')->withInput();
        }
    }

    /**
     * Show the form for editing.
     */
    public function edit($id)
    {
        $registro = VoceroHistorial::findOrFail($id);
        $voceros = Vocero::orderBy('nombre', 'asc')->get();
        $mesas = MesaTecnica::orderBy('nombre', 'asc')->get();
        return view('organizacion.voceroshistorial.edit', compact('registro', 'voceros', 'mesas'));
    }

    /**
     * Update the history entry.
     */
    public function update(Request $request, $id)
    {
        $registro = VoceroHistorial::findOrFail($id);
        
        $request->validate([
            'vocero_id' => [
                'required',
                'exists:vocero,vocero_id',
                // Ignora el registro actual para permitir editar otros campos
                Rule::unique('vocero_historial', 'vocero_id')->ignore($id, 'vocero_historial_id'),
            ],
            'mesa_tecnica_id' => 'required|exists:mesa_tecnica,mesa_tecnica_id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'motivo_salida' => 'nullable|string',
        ], [
            'vocero_id.unique' => 'Ya existe otro registro en el historial para este vocero.',
        ]);

        try {
            $registro->update($request->all());
            return redirect()->route('voceros-historial.index')->with('success', 'Registro actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar historial de vocero: " . $e->getMessage());
            return back()->with('error', 'Error al actualizar el registro.');
        }
    }

    /**
     * Remove the entry.
     */
    public function destroy($id)
    {
        try {
            $registro = VoceroHistorial::findOrFail($id);
            $registro->delete();
            return redirect()->route('voceros-historial.index')->with('success', 'Registro eliminado del historial.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar registro: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
}