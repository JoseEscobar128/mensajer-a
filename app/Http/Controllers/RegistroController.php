<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    public function registro(Request $request)
    {
        try {
            // Validar los datos ingresados
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:registros|max:255',
                'password' => 'required|string|min:8',
            ]);

            // Crear el usuario
            $user = Registro::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => hash('sha256', $request->password), // Usando SHA-256
            ]);

            // Iniciar sesión automáticamente al usuario
            Auth::login($user);

            // Redirigir a la página de chat o a donde desees después del registro
            return redirect()->route('chat')->with('success', 'Usuario creado exitosamente');

        } catch (ValidationException $e) {
            // En caso de error de validación, redirigir de vuelta con los errores
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // En caso de error general, redirigir de vuelta con un mensaje de error
            return redirect()->back()->with('error', 'Error al crear el usuario, por favor intenta de nuevo.');
        }
    }
}
