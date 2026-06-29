<?php

namespace App\Http\Controllers;

use App\Models\Vocero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class VoceroController extends Controller
{
    /**
     * Muestra el listado de voceros con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $voceros = Vocero::when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('apellido', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('cedula', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('telefono', 'LIKE', '%' . $buscar . '%');
            })
            ->orderBy('vocero_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('organizacion.voceros.index', compact('voceros', 'buscar'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('organizacion.voceros.create');
    }

    /**
     * Guarda el vocero en la base de datos con validaciones estrictas de números.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'cedula'   => [
                'required',
                'numeric',           // Solo números, elimina posibilidad de "V-"
                'digits_between:1,8', // Máximo 8 dígitos exactos
                Rule::unique('vocero', 'cedula'),
            ],
            'telefono' => 'nullable|numeric|digits:11', // Exactamente 11 números, sin guiones
            'direccion'=> 'nullable|string|max:200',
            'genero'   => 'nullable|string|max:20',
            'estado'   => 'required|in:activo,inactivo',
        ], [
            'nombre.required' => 'El nombre del vocero es obligatorio.',
            'cedula.required' => 'El número de cédula es obligatorio.',
            'cedula.numeric'  => 'La cédula debe contener solo números (sin letras ni símbolos).',
            'cedula.digits_between' => 'La cédula debe tener entre 1 y 8 dígitos.',
            'cedula.unique'   => 'Esta cédula ya se encuentra registrada en el sistema.',
            'telefono.numeric'=> 'El teléfono debe contener solo números.',
            'telefono.digits' => 'El teléfono debe tener exactamente 11 números (ej. 04241234567).',
            'estado.required' => 'Debe seleccionar el estado del vocero.'
        ]);

        try {
            Vocero::create($request->all());

            return redirect()->route('voceros.index')
                ->with('success', 'Vocero registrado exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear vocero: " . $e->getMessage());
            return back()->with('error', 'No se pudo registrar el vocero.')->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $vocero = Vocero::findOrFail($id);
        return view('organizacion.voceros.edit', compact('vocero'));
    }

    /**
     * Actualiza el vocero manteniendo las restricciones numéricas.
     */
    public function update(Request $request, $id)
    {
        $vocero = Vocero::findOrFail($id);
        
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'cedula'   => [
                'required',
                'numeric',
                'digits_between:1,8',
                Rule::unique('vocero', 'cedula')->ignore($id, 'vocero_id'),
            ],
            'telefono' => 'nullable|numeric|digits:11',
            'direccion'=> 'nullable|string|max:200',
            'genero'   => 'nullable|string|max:20',
            'estado'   => 'required|in:activo,inactivo',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.numeric'  => 'La cédula debe contener solo números.',
            'cedula.digits_between' => 'La cédula no debe exceder los 8 dígitos.',
            'cedula.unique'   => 'Ya existe otro vocero con este número de cédula.',
            'telefono.numeric'=> 'El teléfono debe contener solo números.',
            'telefono.digits' => 'El teléfono debe tener exactamente 11 números.',
        ]);

        try {
            $vocero->update($request->all());
            return redirect()->route('voceros.index')->with('success', 'Datos del vocero actualizados.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar vocero: " . $e->getMessage());
            return back()->with('error', 'Error al actualizar los datos del vocero.')->withInput();
        }
    }

    /**
     * Elimina el vocero.
     */
    public function destroy($id)
    {
        try {
            $vocero = Vocero::findOrFail($id);
            $vocero->delete();
            return redirect()->route('voceros.index')->with('success', 'Vocero eliminado.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar vocero ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el vocero.');
        }
    }
}