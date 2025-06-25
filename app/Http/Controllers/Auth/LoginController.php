<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Mostra il form di login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Gestione POST del login
    public function login(Request $request)
    {
        // Validazione delle credenziali
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tentativo di login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Login fallito → errore localizzato
        return back()->withInput($request->only('email'))
                     ->with('error', __('ui.login_failed'));
    }
}
