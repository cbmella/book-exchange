<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register()
    {
        $validatedData = $this->validateRegistration(request());

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => strtolower($validatedData['email']), // Convert email to lowercase
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $validatedData = $this->validateLogin($request);
        $validatedData['email'] = strtolower($validatedData['email']); // Convert email to lowercase

        if (!$token = JWTAuth::attempt($validatedData)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
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
        $email = $request->input('email');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($email) {
                    $existingUser = User::where('email', strtolower($email))->first();
                    if ($existingUser) {
                        $fail('The email has already been taken.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();
        $validatedData['email'] = strtolower($validatedData['email']); // Convert email to lowercase

        return $validatedData;
    }


    private function validateLogin(Request $request): array
    {
        return $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }
}
