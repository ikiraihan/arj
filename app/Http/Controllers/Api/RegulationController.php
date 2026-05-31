<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\RegulationFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class RegulationController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = RegulationFile::query();

        // SEARCH
        if ($request->search_regulation) {

            $query->where(function ($q) use ($request) {

                $q->where('title', 'like', '%' . $request->search_regulation . '%')
                ->orWhere('description', 'like', '%' . $request->search_regulation . '%');

            });
        }

        // SORTING
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $columns = $request->input('columns');

        $orderColumn = $columns[$orderColumnIndex]['name'] ?? 'created_at';

        $allowedColumns = [
            'title',
            'is_active',
            'created_at',
        ];

        if (!in_array($orderColumn, $allowedColumns)) {
            $orderColumn = 'created_at';
        }

        $query->orderBy($orderColumn, $orderDirection);

        $recordsFiltered = $query->count();
        $recordsTotal    = RegulationFile::count();

        $regulations = $query
            ->skip($start)
            ->take($length)
            ->get()
            ->transform(function ($item) {

                $item->created_at_formatted = $item->created_at
                    ? Carbon::parse($item->created_at)->translatedFormat('d F Y H:i')
                    : null;

                $item->file_url = $item->path
                    ? asset('storage/' . $item->path)
                    : null;

                $item->status_badge = $item->is_active
                    ? 'Aktif'
                    : 'Nonaktif';

                return $item;
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $regulations
        ]);
    }

    /**
     * Show regulation detail
     */
    public function show($id): JsonResponse
    {
        $regulation = RegulationFile::findOrFail($id);

        $regulation->created_at_formatted = $regulation->created_at
                    ? Carbon::parse($regulation->created_at)->translatedFormat('d F Y H:i')
                    : null;

        $regulation->file_url = $regulation->path
                    ? asset('storage/' . $regulation->path)
                    : null;

        $regulation->status_badge = $regulation->is_active
                    ? 'Aktif'
                    : 'Nonaktif';


        return response()->json([
            'status' => true,
            'data' => $regulation
        ]);
    }

    /**
     * Update racer
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {

            $regulation = RegulationFile::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'nullable|file|mimes:pdf',
                'is_active' => 'nullable|boolean',
            ], [
                'title.required' => 'Judul wajib diisi',
                'file.required' => 'File regulasi wajib diupload',
                'file.mimes' => 'File harus berformat PDF',
                'file.max' => 'Ukuran file maksimal 10 MB',
                'is_active.required' => 'Status aktif wajib dipilih',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // UPDATE DATA
            $regulation->title      = $request->title;
            $regulation->description     = $request->description;
            $regulation->is_active            = $request->is_active ?? false;

            // PHOTO
            if ($request->hasFile('path')) {

                if ($regulation->path && Storage::disk('public')->exists($regulation->path)) {

                    Storage::disk('public')->delete($regulation->path);
                }

                $regulation->path = $request
                    ->file('file')
                    ->store('regulations', 'public');
            }

            $regulation->save();

            return response()->json([
                'success' => true,
                'message' => 'Data regulation berhasil diperbarui',
                'data'    => $regulation
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Data regulation tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {

            $regulation = RegulationFile::findOrFail($id);

            if ($regulation->file) {
                Storage::disk('public')->delete($regulation->file);
            }

            $regulation->delete();

            return response()->json([
                'status' => true,
                'message' => 'Regulasi berhasil dihapus'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Data regulasi tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }
}
