<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * Halaman Landing
     */
    public function index()
    {
        $users = User::all();

        return view('admin.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required',
                'phone' => 'required|min:10|max:15',
                'password' => 'required|min:6|confirmed',
            ], [
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'role.required' => 'Role wajib dipilih',
                'phone.required' => 'Nomor telepon wajib diisi',
                'password.required' => 'Password wajib diisi',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone_number' => $validated['phone'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()
                ->back()
                ->with('success', 'User berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('open_offcanvas', 'offcanvas_add');

        } catch (\Exception $e) {

            Log::error('Gagal tambah user: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan user')
                ->withInput()
                ->with('open_offcanvas', 'offcanvas_add');
        }
    }
}
