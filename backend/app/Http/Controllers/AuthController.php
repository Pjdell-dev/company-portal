<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'company_code' => 'required|string'
        ]);

        // Attempt to authenticate via session (Sanctum SPA cookie flow)
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        if (!$user->is_active) {
            Auth::logout();
            return response()->json(['error' => 'Account inactive'], 403);
        }

        $company = Company::where('code', $request->company_code)->first();
        if (!$company || $user->company_id !== $company->id) {
            Auth::logout();
            return response()->json(['error' => 'Company mismatch'], 401);
        }

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        return response()->json([
            'user' => $user,
            'company' => $company
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}