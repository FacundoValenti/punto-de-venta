<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        // Verificar si el usuario ya está autenticado
        if (Auth::check()) {
            return redirect()->route('panel');
        }

        return view('auth.login');
    }

    public function login(loginRequest $request)
    {
        // Validar credenciales
        if (!Auth::validate($request->only('email', 'password'))) {
            return redirect()->to('login')->withErrors('Datos incorrectos');
        }

        // Crear una sesión para el usuario
        $user = Auth::getProvider()->retrieveByCredentials($request->only('email', 'password'));
        Auth::login($user);

        return redirect()->route('panel')->with('success','Bienvenido'.$user->name);
    }
}
