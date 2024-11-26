<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $model = $this->getModel($request->role);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $model::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json([
            'message' => 'Registrado com sucesso.',
            'user' => $model,
        ], 201);
    }

    public function login(Request $request)
    {
        $model = $this->getModel($request->role);

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        $user = $model::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credênciais incorretas.'], 403);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('a-system')->plainTextToken,
            'message' => "Logado com sucesso."
        ]);
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout bem sucedido']);
    }

    public function verify(Request $request)
    {
        return $request->user();
    }

    protected function getModel($role)
    {
        if ($role) {
            return new User();
        } else {
            return new Affiliate();
        }
    }
}
