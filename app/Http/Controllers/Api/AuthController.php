<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:4|confirmed',
        // ❌ 'rol' no se valida aquí
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'rol' => 'usuario' // ✅ Asignamos el rol aquí por defecto
    ]);

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'rol' => $user->rol // ✅ Importante: incluir el rol en la respuesta
        ],
        'token' => $user->createToken('api-token')->plainTextToken,
        'token_type' => 'Bearer'
    ], 201);
}


   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
         'token_type' => 'Bearer'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            ['Las credenciales son incorrectas.'],
        ]);
    }

    return response()->json([
        'user' => $user,
        'token' => $user->createToken('api-token')->plainTextToken,
        'token_type' => 'Bearer',
        'rol' => $user->rol
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);

    }
}

