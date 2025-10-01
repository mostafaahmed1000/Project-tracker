<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate(
                [
                    'email' => 'required|email',
                    'password' => 'required|string|min:6',
                ],
                [
                    'email.required' => 'Email is required.',
                    'email.email' => 'Please provide a valid email address.',
                    'password.required' => 'Password is required.',
                    'password.min' => 'Password must be at least 6 characters.',
                ]
            );


            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Login failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete();
            return response()->json(['message' => 'Logged out'], 204);
        }
        return response()->json(['message' => 'No token found'], 401);
    }
}
