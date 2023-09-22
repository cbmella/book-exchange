<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $this->validateRegistration($request);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $validatedData = $this->validateLogin($request);

        if (!$token = JWTAuth::attempt($validatedData)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json(['token' => $token], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully'], Response::HTTP_OK);
    }

    private function validateRegistration(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    private function validateLogin(Request $request): array
    {
        return $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }
}
