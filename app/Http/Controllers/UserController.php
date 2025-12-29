<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    /**
     * Aplica o middleware de permissão no construtor.
     */
    public function __construct()
    {
        $this->middleware('permission:manage users');
    }

    /**
     * Lista todos os usuários.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('view_users.index', compact('users'));
    }

    /**
     * Exibe o formulário para criar um novo usuário.
     */
    public function create()
    {
        $roles = Role::all();
        return view('view_users.create', compact('roles'));
    }

    /**
     * Armazena um novo usuário.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Exibe o formulário para editar um usuário.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('view_users.edit', compact('user', 'roles'));
    }

    /**
     * Atualiza um usuário existente.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->has('password') && $request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('view_users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove um usuário.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('view_users.index')->with('success', 'Usuário deletado com sucesso!');
    }
}