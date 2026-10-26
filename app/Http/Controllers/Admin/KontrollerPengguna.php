<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KontrollerPengguna extends Controller
{
    public function indeks(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%{$cari}%")
                  ->orWhere('email', 'like', "%{$cari}%")
                  ->orWhere('username', 'like', "%{$cari}%");
            });
        }

        $semuaPengguna = $query->latest()->paginate(10)->withQueryString();
        return view('admin.pengguna.indeks', ['users' => $semuaPengguna]);
    }

    public function buat()
    {
        return view('admin.pengguna.buat');
    }

    public function simpan(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,staff',
            'phone'    => 'nullable|string|max:20',
            'status'   => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function detail(User $user)
    {
        $user->load(['sales' => function ($q) {
            $q->latest()->take(5);
        }]);
        return view('admin.pengguna.detail', compact('user'));
    }

    public function ubah(User $user)
    {
        return view('admin.pengguna.ubah', compact('user'));
    }

    public function perbarui(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:admin,staff',
            'phone'    => 'nullable|string|max:20',
            'status'   => 'required|in:active,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function ubahStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $statusBaru = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $statusBaru]);

        return back()->with('success', 'Status pengguna "' . $user->name . '" berhasil diubah menjadi ' . $statusBaru . '.');
    }

    public function index(Request $request)
    {
        return $this->indeks($request);
    }

    public function create()
    {
        return $this->buat();
    }

    public function store(Request $request)
    {
        return $this->simpan($request);
    }

    public function show(User $user)
    {
        return $this->detail($user);
    }

    public function edit(User $user)
    {
        return $this->ubah($user);
    }

    public function update(Request $request, User $user)
    {
        return $this->perbarui($request, $user);
    }

    public function toggleStatus(User $user)
    {
        return $this->ubahStatus($user);
    }
}
