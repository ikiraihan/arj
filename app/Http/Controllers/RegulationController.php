<?php

namespace App\Http\Controllers;

use App\Models\RegulationFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegulationController extends Controller
{
    /**
     * Halaman Landing
     */
    public function index()
    {
        return view('admin.regulation.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul wajib diisi',
            'file.required' => 'File regulasi wajib diupload',
            'file.mimes' => 'File harus berformat PDF',
            'file.max' => 'Ukuran file maksimal 10 MB',
            'is_active.nullable' => 'Status aktif tidak valid',
        ]);

        DB::beginTransaction();

        try {

            $file = $request->file('file')
                ? $request->file('file')->store('regulations', 'public')
                : null;

            RegulationFile::create([
                'title' => $request->title,
                'description' => $request->description,
                'path' => $file,
                'is_active' => $request->boolean('is_active') ?? false,
            ]);

            DB::commit();

            return redirect()
                ->route('regulations')
                ->with('success', 'Regulasi berhasil ditambahkan');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Gagal menyimpan regulasi', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan regulasi');
        }
    }
}
