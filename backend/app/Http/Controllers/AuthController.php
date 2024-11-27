<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Bissolli\ValidadorCpfCnpj\CPF;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $model = $this->getModel($request->role);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];

        if ($request->role === 'affiliate') {
            $rules['cpf'] = [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!(new CPF($value))->isValid()) {
                        $fail("O campo {$attribute} não é um CPF válido.");
                    }
                },
            ];
            $rules['phone'] = 'required|string';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:255';
            $rules['state'] = 'required|string|max:2';
            $rules['birthday'] = 'required|date';
        }

        $validatedData = $request->validate($rules);
        $userData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ];
        if ($request->role === 'affiliate') {

            $userData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'cpf' => $validatedData['cpf'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'birthday' => Carbon::parse($request->birthday)->format('Y-m-d') ?? null,
                'address' => $validatedData['address'] ?? null,
                'city' => $validatedData['city'] ?? null,
                'state' => $validatedData['state'] ?? null,
            ];
        }
        $user = $model::create($userData);

        return response()->json([
            'message' => 'Registrado com sucesso.',
            'user' => $user,
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
            return response()->json(['message' => 'Credenciais incorretas.'], 403);
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

        return response()->json(['message' => 'Logout bem sucedido', 'status' => 200]);
    }

    public function verify(Request $request)
    {
        return $request->user();
    }

    protected function getModel($role)
    {
        if ($role === 'affiliate') {
            return new Affiliate();
        } else {
            return new User();
        }
    }
}