<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function index(Request $request): JsonResponse
    {
        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = User::query();

        // SEARCH
        if ($request->search_user) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search_user . '%')
                ->orWhere('email', 'like', '%' . $request->search_user . '%')
                ->orWhere('phone_number', 'like', '%' . $request->search_user . '%');

            });
        }

        // SORTING
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $columns = $request->input('columns');

        $orderColumn = $columns[$orderColumnIndex]['name'] ?? 'created_at';

        // whitelist biar aman
        $allowedColumns = [
            'name',
            'email',
            'phone_number',
            'created_at',
        ];

        if (!in_array($orderColumn, $allowedColumns)) {
            $orderColumn = 'created_at';
        }

        $query->orderBy($orderColumn, $orderDirection);

        $recordsFiltered = $query->count();
        $recordsTotal    = User::count();

        $users = $query
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $users
        ]);
    }

        /**
     * SHOW - ambil data user untuk edit
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    /**
     * UPDATE - edit user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'     => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'required|in:superadmin,admin,user',
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
        ], [
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'role.required' => 'Role wajib dipilih',
                'phone.required' => 'Nomor telepon wajib diisi',
                // 'password.required' => 'Password wajib diisi',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();

            // ambil error pertama dari semua field
            $firstError = $errors->first();

            return response()->json([
                'message' => $firstError,
                'errors' => $errors
            ], 422);

        }

        // update data
        $user->name  = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->role  = $request->role ?? $user->role;
        $user->phone_number = $request->phone ?? $user->phone_number;

        // password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}
