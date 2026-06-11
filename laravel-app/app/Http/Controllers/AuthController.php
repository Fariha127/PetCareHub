<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid login credentials.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route($this->dashboardRoute(Auth::user()->role));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'USER';

        $user = User::create($data);
        Auth::login($user);

        return redirect()->route($this->dashboardRoute($user->role));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    private function dashboardRoute(string $role): string
    {
        return match ($role) {
            'ADMIN' => 'dashboard.admin',
            'SHELTER_STAFF' => 'dashboard.shelter',
            'VETERINARIAN' => 'dashboard.vet',
            default => 'dashboard.user',
        };
    }
}
