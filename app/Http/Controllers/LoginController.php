<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha', // Validar el CAPTCHA
        ]);
    
        // Buscar al usuario por email
        $user = Registro::where('email', $request->email)->first();
    
        // Si el usuario existe y la contraseña es correcta
        if ($user && hash('sha256', $request->password) === $user->password) {
            // Generar un código de verificación aleatorio
            $verificationCode = Str::random(6); // Puedes usar un código más adecuado para tu caso
        
            // Guardar el código en la sesión del usuario
            session(['verification_code' => $verificationCode, 'email_verified_user' => $user->id]);
        
            // Enviar el código de verificación por correo
            Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($verificationCode));
        
            // Redirigir a la vista de verificación
            return redirect()->route('verification.form');
        }
    
        // Si las credenciales son incorrectas
        return redirect()->back()->with('error', 'Credenciales incorrectas');
    }
    
    public function logout(Request $request)
    {
        // Cierra la sesión del usuario
        Auth::logout();
    
        // Invalidar la sesión actual del usuario
        $request->session()->invalidate();
    
        // Regenerar el token CSRF para mayor seguridad
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'Sesión cerrada exitosamente.');
    }
    
    public function verifyCode(Request $request)
    {
        // Validar que se haya ingresado un código
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        // Obtener el código almacenado en la sesión
        $storedCode = session('verification_code');
        $userId = session('email_verified_user');

        // Comprobar si el código coincide
        if ($request->verification_code === $storedCode) {
            // Marcar al usuario como verificado y autenticar
            $user = Registro::find($userId);
            Auth::login($user);

            // Limpiar los datos de la sesión
            session()->forget(['verification_code', 'email_verified_user']);

            // Redirigir al usuario al chat o página principal
            return redirect()->route('chat')->with('success', 'Verificación completada');
        }

        // Si el código no coincide, redirigir con error
        return redirect()->back()->with('error', 'Código de verificación incorrecto');
    }
}
