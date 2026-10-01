<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private const SESSION_KEY = 'users.index_url';

    public function __construct()
    {
        $this->middleware('permission:manage users');
    }

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('users.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $users = User::with('roles')
            ->when($request->nome, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->nome . '%');
            })
            ->when($request->email, function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->email . '%');
            })
            ->when($request->papel, function ($query) use ($request) {
                $query->whereHas('roles', function ($roleQuery) use ($request) {
                    $roleQuery->where('name', $request->papel);
                });
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('view_users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('view_users.create', [
            'roles' => $roles,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'role.required' => 'O papel é obrigatório.',
            'role.exists' => 'O papel selecionado é inválido.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect($this->urlIndex())
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $user = User::with('roles')->findOrFail($id);

        return view('view_users.show', [
            'user' => $user,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('view_users.edit', [
            'user' => $user,
            'roles' => $roles,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'role.required' => 'O papel é obrigatório.',
            'role.exists' => 'O papel selecionado é inválido.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->syncRoles([$request->role]);

        return redirect($this->urlIndex())
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect($this->urlIndex())
                ->with('error', 'Você não pode excluir o usuário que está atualmente logado.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário deletado com sucesso!');
    }
}
