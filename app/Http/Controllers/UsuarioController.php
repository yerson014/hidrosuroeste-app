<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Muestra la lista de usuarios con opción de búsqueda.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $usuarios = User::when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('apellido', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('cedula', 'LIKE', '%' . $buscar . '%')
                             ->orWhere('correo', 'LIKE', '%' . $buscar . '%');
            })
            ->orderBy('usuario_id', 'desc')
            ->paginate(8) 
            ->withQueryString();

        return view('configuracion.usuarios.index', compact('usuarios', 'buscar'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('configuracion.usuarios.create');
    }

    /**
     * Guarda el nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellido'         => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'rol'              => 'required|in:administrador,usuario,visitante',
            'password'         => 'required|min:8|confirmed',
            // Validación de Cédula Única
            'cedula' => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuario', 'cedula'),
            ],
            // Validación de Correo Único
            'correo' => [
                'required',
                'email',
                'max:150',
                Rule::unique('usuario', 'correo'),
            ],
        ], [
            'cedula.required' => 'El número de cédula es obligatorio.',
            'cedula.unique'   => 'Ya existe un usuario registrado con esta cédula.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.unique'   => 'Este correo electrónico ya se encuentra en uso por otro usuario.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        try {
            User::create([
                'nombre'           => $request->nombre,
                'apellido'         => $request->apellido,
                'cedula'           => $request->cedula,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'correo'           => $request->correo,
                'rol'              => $request->rol,
                'password'         => Hash::make($request->password),
            ]);

            return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al crear usuario: " . $e->getMessage());
            return back()->withInput()->with('error', 'No se pudo crear el usuario. Verifique los datos.');
        }
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit($id)
    {
        $usuario = User::where('usuario_id', $id)->firstOrFail();
        return view('configuracion.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza los datos del usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = User::where('usuario_id', $id)->firstOrFail();

        $request->validate([
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:255',
            'rol'              => 'required|in:administrador,usuario,visitante',
            'fecha_nacimiento' => 'required|date',
            'password'         => 'nullable|min:8|confirmed',
            // Validación de Cédula Única ignorando el registro actual
            'cedula' => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuario', 'cedula')->ignore($usuario->usuario_id, 'usuario_id'),
            ],
            // Validación de Correo Único ignorando el registro actual
            'correo' => [
                'required',
                'email',
                'max:150',
                Rule::unique('usuario', 'correo')->ignore($usuario->usuario_id, 'usuario_id'),
            ],
        ], [
            'cedula.unique' => 'Esta cédula ya pertenece a otro usuario registrado.',
            'correo.unique' => 'Este correo ya está siendo utilizado por otra cuenta.',
            'password.min'    => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        try {
            $usuario->nombre           = $request->nombre;
            $usuario->apellido         = $request->apellido;
            $usuario->cedula           = $request->cedula;
            $usuario->correo           = $request->correo;
            $usuario->rol              = $request->rol;
            $usuario->fecha_nacimiento = $request->fecha_nacimiento;

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }

            $usuario->save();
            
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar usuario: " . $e->getMessage());
            return back()->with('error', 'No se pudo actualizar el usuario.')->withInput();
        }
    }

    /**
     * Elimina a un usuario.
     */
    public function destroy($id)
    {
        try {
            $usuario = User::where('usuario_id', $id)->firstOrFail();
            
            if ($usuario->usuario_id === Auth::user()->usuario_id) {
                return back()->with('error', 'No puedes eliminar tu propia cuenta de usuario.');
            }

            $usuario->delete();
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar usuario: " . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al intentar eliminar el registro.');
        }
    }
}