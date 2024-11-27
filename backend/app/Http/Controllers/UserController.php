<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Listar todos os usuários com paginação
    public function index(Request $request)
    {
        $users = User::paginate(10);
        return response()->json($users);
    }

    // Mostrar detalhes de um usuário específico
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    // Atualizar status de um usuário (Ativo/Inativo)
    public function updateStatus($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->status = $request->input('status', 1); // Se não passar status, assume ativo
        $user->save();

        return response()->json(['message' => 'Status atualizado com sucesso.']);
    }

    // Atualizar informações de um usuário (name, email)
    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json(['message' => 'Usuário atualizado com sucesso.', 'user' => $user]);
    }

    // Criar um novo usuário
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create($request->only(['name', 'email', 'status']));

        return response()->json(['message' => 'Usuário criado com sucesso.', 'user' => $user], 201);
    }

    // Deletar um usuário
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso.']);
    }
}