<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(\App\Http\Requests\Auth\LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $u = $request->user();

        // Gunakan Spatie langsung (guard web by default dari modelmu)
        if (method_exists($u, 'hasRole') && $u->hasRole('superadmin')) {
            return redirect(route('superadmin.dashboard'));   // role name di DB kamu: 'superadmin'
        }

        if (method_exists($u, 'hasAnyRole') && $u->hasAnyRole(['ADMIN','admin'])) {
            return redirect(route('admin.dashboard'));        // cover 'ADMIN' uppercase atau 'admin' lowercase
        }

        return redirect(route('user.donations.dashboard'));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
