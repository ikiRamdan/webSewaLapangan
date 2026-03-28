<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LogHelper;


class UserController extends Controller
{
   public function index(Request $request)
{
    $query = User::query();

    if ($request->name) {
        $query->where('name','like','%'.$request->name.'%');
    }

    if ($request->role) {
        $query->where('role',$request->role);
    }

    $users = $query->paginate(10)->withQueryString();

    return view('admin.users.index', compact('users'));
}

    public function create()
    {
        return view('admin.users.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,owner,kasir',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true
        ]);

        // LOGGING
        LogHelper::store('Tambah User', "Membuat user baru: {$user->name} ({$user->role})");

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,owner,kasir',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // LOGGING
        LogHelper::store('Update User', "Mengubah data user: {$user->name}");

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

   public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        // LOGGING
        LogHelper::store('Hapus User', "Menghapus user: {$name}");

        return back()->with('success','User berhasil dihapus');
    }
}