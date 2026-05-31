<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Racer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RacerController extends Controller
{
    public function index(Request $request, $userId): JsonResponse
    {

        if ((int) $userId !== (int) auth()->id()) {

            return response()->json([
                'message' => 'Unauthorized'
            ], 403);

        }

        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = Racer::query();

        // FILTER BY USER ID
        $query->where('user_id', $userId);

        // SEARCH
        if ($request->search_racer) {

            $query->where(function ($q) use ($request) {

                $q->where('full_name', 'like', '%' . $request->search_racer . '%')
                ->orWhere('short_name', 'like', '%' . $request->search_racer . '%')
                ->orWhere('phone_number', 'like', '%' . $request->search_racer . '%')
                ->orWhere('racer_number', 'like', '%' . $request->search_racer . '%');

            });
        }

        // SORTING
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $columns = $request->input('columns');

        $orderColumn = $columns[$orderColumnIndex]['name'] ?? 'created_at';

        // whitelist biar aman
        $allowedColumns = [
            'full_name',
            'short_name',
            'phone_number',
            'racer_number',
            'created_at',
        ];

        if (!in_array($orderColumn, $allowedColumns)) {
            $orderColumn = 'created_at';
        }

        $query->orderBy($orderColumn, $orderDirection);

        $recordsFiltered = $query->count();
        $recordsTotal    = Racer::count();

        $users = $query
            ->skip($start)
            ->take($length)
            ->get()
            ->transform(function ($item) {

                $item->birth_date = $item->birth_date
                ? Carbon::parse($item->birth_date)->format('Y-m-d')
                : null;

                $item ->birth_date_formatted = $item->birth_date ? Carbon::parse($item->birth_date)->translatedFormat('d F Y') : null;

                $item->photo_url = $item->photo
                    ? asset('storage/' . $item->photo)
                    : null;

                $item->kta_url = $item->kta
                    ? asset('storage/' . $item->kta)
                    : null;

                $item->kis_url = $item->kis
                    ? asset('storage/' . $item->kis)
                    : null;

                return $item;
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $users
        ]);
    }

    /**
     * Show racer detail
     */
    public function show($id): JsonResponse
    {
        $racer = Racer::findOrFail($id);

        $racer->birth_date_input = $racer->birth_date
            ? Carbon::parse($racer->birth_date)->format('Y-m-d')
            : null;

        $racer->birth_date_formatted = $racer->birth_date
            ? Carbon::parse($racer->birth_date)->translatedFormat('d F Y')
            : null;

        $racer->photo_url = $racer->photo
            ? asset('storage/' . $racer->photo)
            : null;

        $racer->kta_url = $racer->kta
            ? asset('storage/' . $racer->kta)
            : null;

        $racer->kis_url = $racer->kis
            ? asset('storage/' . $racer->kis)
            : null;

        return response()->json([
            'status' => true,
            'data' => $racer
        ]);
    }

    /**
     * Update racer
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {

            $racer = Racer::findOrFail($id);

            $validator = Validator::make($request->all(), [

                'full_name'      => 'required|string|max:255',
                'short_name'     => 'required|string|max:255',
                'nik'            => 'required|string|max:50',
                'phone_number'   => 'nullable|string|max:20',
                'hometown'       => 'required|string|max:255',
                'racer_number'   => 'required|numeric',
                'birth_location' => 'required|string|max:255',
                'birth_date'     => 'required|date',

                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
                'kis'   => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
                'kta'   => 'nullable|image|mimes:jpg,jpeg,png|max:10240',

            ], [

                'full_name.required'      => 'Nama lengkap wajib diisi',
                'short_name.required'     => 'Nama alias wajib diisi',
                'nik.required'            => 'NIK wajib diisi',
                'hometown.required'       => 'Asal kota wajib diisi',
                'racer_number.required'   => 'Nomor start wajib diisi',
                'birth_location.required' => 'Tempat lahir wajib diisi',
                'birth_date.required'     => 'Tanggal lahir wajib diisi',

            ]);

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // UPDATE DATA
            $racer->full_name      = $request->full_name;
            $racer->short_name     = $request->short_name;
            $racer->nik            = $request->nik;
            $racer->phone_number   = $request->phone_number;
            $racer->hometown       = $request->hometown;
            $racer->racer_number   = $request->racer_number;
            $racer->birth_location = $request->birth_location;
            $racer->birth_date     = $request->birth_date;

            // PHOTO
            if ($request->hasFile('photo')) {

                if ($racer->photo && Storage::disk('public')->exists($racer->photo)) {

                    Storage::disk('public')->delete($racer->photo);
                }

                $racer->photo = $request
                    ->file('photo')
                    ->store('racers/photo', 'public');
            }

            // KIS
            if ($request->hasFile('kis')) {

                if ($racer->kis && Storage::disk('public')->exists($racer->kis)) {

                    Storage::disk('public')->delete($racer->kis);
                }

                $racer->kis = $request
                    ->file('kis')
                    ->store('racers/kis', 'public');
            }

            // KTA
            if ($request->hasFile('kta')) {

                if ($racer->kta && Storage::disk('public')->exists($racer->kta)) {

                    Storage::disk('public')->delete($racer->kta);
                }

                $racer->kta = $request
                    ->file('kta')
                    ->store('racers/kta', 'public');
            }

            $racer->save();

            return response()->json([
                'success' => true,
                'message' => 'Data racer berhasil diperbarui',
                'data'    => $racer
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Data racer tidak ditemukan'
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
        $racer = Racer::findOrFail($id);

        $racer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Pembalap berhasil dihapus'
        ]);
    }

    public function select(Request $request, $userId = null): JsonResponse
    {
        $search = $request->q;
        $query = Racer::query();

        if ($search) {

            $query->where('full_name', 'like', "%{$search}%");

        }

        if ($userId) {

            $query->where('user_id', '=', $userId);

        }

        $racer = $query
            ->select([
                'id',
                DB::raw('full_name as name')
            ])
            ->limit(20)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $racer
        ]);
    }
}
