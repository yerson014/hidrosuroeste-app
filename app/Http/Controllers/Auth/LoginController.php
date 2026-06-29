<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Muestra el formulario de registro.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Maneja el proceso de inicio de sesión.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'contraseña' => ['required'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'contraseña.required' => 'La contraseña es obligatoria.',
        ]);

        // Intentamos autenticar usando el campo 'correo' y la clave mapeada 'password'
        if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['contraseña']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->only('correo'));
    }

    /**
     * Procesa el registro de un nuevo usuario.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|unique:usuario,cedula|max:20',
            'email' => 'required|string|email|unique:usuario,correo|max:100',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'cedula.unique' => 'Esta cédula ya existe.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cedula' => $request->cedula,
            'correo' => $request->email,
            'password' => Hash::make($request->password),
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);

        // Login automático tras el registro
        Auth::login($user);

        return redirect()->route('login')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
    }

    /**
     * Cierra la sesión del usuario de forma segura.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidar la sesión actual
        $request->session()->invalidate();

        // Regenerar el token CSRF para prevenir ataques de fijación de sesión
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Has cerrado sesión exitosamente.');
    }
}