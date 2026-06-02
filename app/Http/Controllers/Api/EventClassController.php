<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\EventClass;
use Carbon\Carbon;

class EventClassController extends Controller
{
    /**
     * Get all users
     */
    public function index(Request $request, $eventId): JsonResponse
    {
        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = EventClass::where('event_id', $eventId)->select([
            'id',
            'name',
            'price',
            'price_fine',
            'cc',
            'notes',
            'quota',
            'created_at'
        ]);

        // SEARCH
        if ($request->search_event) {

            $search = $request->search_event;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                ->orWhere('cc', 'like', "%{$search}%")
                ->orWhere('notes', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%");

            });
        }
        // SORT
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $columns = $request->input('columns');

        $orderColumn = $columns[$orderColumnIndex]['data'] ?? 'created_at';

        $allowedColumns = [
            'name',
            'price',
            'price_fine',
            'cc',
            'notes',
            'quota',
            'created_at',
        ];

        if (!in_array($orderColumn, $allowedColumns)) {
            $orderColumn = 'created_at';
        }

        $query->orderBy($orderColumn, $orderDirection);

        $classes = $query
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'price' => $class->price,
                    'price_formatted' => 'Rp. ' . number_format($class->price, 0, ',', '.'),
                    'price_fine' => $class->price_fine,
                    'price_fine_formatted' => 'Rp. ' . number_format($class->price_fine, 0, ',', '.'),
                    'cc' => $class->cc,
                    'notes' => $class->notes,
                    'quota' => $class->quota,
                    'created_at' => $class->created_at ? Carbon::parse($class->created_at)->translatedFormat('d F Y m:H:i') : null,
                ];
            });
        // dd($classes); // debug
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => EventClass::count(),
            'recordsFiltered' => $query->count(),
            'data' => $classes
        ]);
    }

    /**
     * Store Event
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $validated = $request->validate([
                'event_id' => 'required|exists:events,id',
                'name' => 'required|string|max:255',
                // 'quota' => 'nullable|integer',
                'price' => 'required|integer',
                'price_fine' => 'required|integer',
                'notes' => 'nullable|string',

            ], [
                'name.required' => 'Nama kelas wajib diisi',
                'price.required' => 'Harga kelas wajib diisi',
            ]);

            DB::beginTransaction();

            $event = EventClass::create([
                'event_id' => $validated['event_id'],
                'name' => $validated['name'],
                'price' => $validated['price'],
                'price_fine' => $validated['price_fine'],
                // 'quota' => $validated['quota'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan',
                'data' => $event
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Gagal menambahkan kelas: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan kelas'
            ], 500);
        }
    }

    /**
     * Show event detail
     */
    public function show($id): JsonResponse
    {
        $class = EventClass::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $class
        ]);
    }

    /**
     * Update Event
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {

            $class = EventClass::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                // 'quota' => 'nullable|integer',
                'price' => 'required|integer',
                'price_fine' => 'required|integer',
                'notes' => 'nullable|string',


            ], [
                'name.required' => 'Nama kelas wajib diisi',
                'price.required' => 'Harga kelas wajib diisi',
            ]);

            DB::beginTransaction();

            $class->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'price_fine' => $validated['price_fine'],
                // 'quota' => $validated['quota'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil diupdate',
                'data' => $class
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Gagal update kelas: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate kelas'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $event = EventClass::findOrFail($id);

        $event->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kelas berhasil dihapus'
        ]);
    }
}
