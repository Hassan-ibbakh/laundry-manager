<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // SUPPRIMER la redirection automatique
        // La page de login doit TOUJOURS s'afficher
        return view('laundry.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('laundry')->attempt($credentials, $remember)) {
            // Vérifier si le compte est actif
            if (!auth('laundry')->user()->is_active) {
                Auth::guard('laundry')->logout();
                return back()->withErrors([
                    'email' => 'حسابك غير مفعل. تواصل مع الإدارة.',
                ]);
            }
            $request->session()->regenerate();
            return redirect()->route('laundry.orders.index');
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('laundry')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('laundry.login')->with('success', 'Déconnexion réussie');
    }
}