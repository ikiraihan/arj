<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RegistrationClass;
use App\Models\RegistrationOriginal;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegistrationClassController extends Controller
{
        /**
     * Get all events
     */
    public function index(Request $request, $eventId): JsonResponse
    {
        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = RegistrationClass::where('event_id', $eventId)
        ->whereHas('registration', function ($q) {
            $q->withoutTrashed()
            ->where('race_status', 'approved');
        })
        ->select([
            'id',
            'registration_id',
            'event_id',
            'class_id',
            'invoice_number',
            'racer_number',
            'vehicle',
            'vehicle_number',
            'rangka_number',
            'created_at',
            // 'name_register',
            // 'phone_number_register',
        ])
        ->with([
            'registration',
            'registration.racer',
            'event',
            'eventClass'
        ]);

        // SEARCH
        if ($request->search_registration_class) {

            $search = $request->search_registration_class;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%")
                ->orWhere('kota', 'like', "%{$search}%");

            });
        }
        // SORT
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $columns = $request->input('columns');

        $orderColumn = $columns[$orderColumnIndex]['data'] ?? 'created_at';

        $allowedColumns = [
            'name',
            'venue',
            'created_at',
        ];

        if (!in_array($orderColumn, $allowedColumns)) {
            $orderColumn = 'created_at';
        }

        $query->orderBy($orderColumn, $orderDirection);

        $recordsTotal = RegistrationClass::count();
        $recordsFiltered = (clone $query)->count();

        $eventId = $query->clone()->first()?->event_id;

        $duplicateNumbers = [];

        if ($eventId) {
            // 2. Cari racer_number dari tabel registrations yang dipakai oleh lebih dari 1 racer_id berbeda pada event ini
            $duplicateNumbers = \DB::table('registration_classes')
                ->join('registrations', 'registration_classes.registration_id', '=', 'registrations.id')
                ->where('registration_classes.event_id', $eventId)
                ->where('registrations.race_status', 'approved')
                ->whereNotNull('registrations.racer_number') // Diubah ke registrations
                ->groupBy('registrations.racer_number')      // Diubah ke registrations
                ->havingRaw('COUNT(DISTINCT registrations.racer_id) > 1')
                ->pluck('registrations.racer_number')        // Diubah ke registrations
                ->toArray();
        }

        $registrationClasses = $query
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($registClass) use ($duplicateNumbers) {

                $registration = $registClass->registration;
                $racer = $registration?->racer;
                $event = $registClass->event;
                $eventClass = $registClass->eventClass;
                $isDuplicateNumber = in_array($registration->racer_number, $duplicateNumbers);

                return [

                    'id' => $registClass->id,
                    'invoice_number' => $registClass->invoice_number,
                    'racer_number' => $registClass->racer_number,
                    'is_racer_number_duplicate' => $isDuplicateNumber,
                    'vehicle' => $registClass->vehicle,
                    'vehicle_number' => $registClass->vehicle_number,
                    'rangka_number' => $registClass->rangka_number,
                    'created_at' => $registClass->created_at?->format('Y-m-d H:i'),
                    'name_register' => $registration->name_register,
                    'phone_number_register' => $registration->phone_number_register,

                    'racer' => $racer ? [
                        'id' => $racer->id,
                        'nik' => $racer->nik,
                        'full_name' => $racer->full_name,
                        'short_name' => $racer->short_name,
                        'phone_number' => $racer->phone_number,
                        'birth_location' => $racer->birth_location,
                        'racer_number' => $racer->racer_number,
                        'birth_date' => $racer->birth_date
                            ? Carbon::parse($racer->birth_date)->translatedFormat('d F Y')
                            : null,
                        'hometown' => $racer->hometown,
                        'photo' => $racer->photo
                            ? asset('storage/' . $racer->photo)
                            : null,
                        'kta' => $racer->kta
                            ? asset('storage/' . $racer->kta)
                            : null,
                        'kis' => $racer->kis
                            ? asset('storage/' . $racer->kis)
                            : null,
                        'is_photo' => $racer->photo
                            ? true
                            : false,
                        'is_kta' => $racer->kta
                            ? true
                            : false,
                        'is_kis' => $racer->kis
                            ? true
                            : false,
                        'user_name' => $racer->user?->name,
                        'user_phone_number' => $racer->user?->phone_number,
                    ] : null,
                    'registration' => $registration,
                    'event' => $event ? [
                            'id' => $event->id,
                            'name' => $event->name,
                        ] : null,
                    'event_class' => $eventClass ? [
                        'id' => $eventClass->id,
                        'name' => $eventClass->name,
                    ] : null,
                ];
            });
        // dd($registrationClasses); // debug
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $registrationClasses
        ]);
    }

    public function indexOriginal(Request $request, $eventId): JsonResponse
    {
        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = RegistrationOriginal::where('event_id', $eventId)
        ->whereHas('registration', function ($q) {
            $q->withoutTrashed()
            ->where('race_status', 'approved');
        })
        ->select([
            'id',
            'registration_id',
            'event_id',
            'class_id',
            'user_id',
            'team_name',
            'invoice_number',
            'racer_number',
            'vehicle',
            'vehicle_number',
            'rangka_number',
            'created_at',
            'name_register',
            'phone_number_register',
        ])
        ->with([
            'registration',
            'registration.racer',
            'event',
            'eventClass'
        ]);

        // SEARCH
        if ($request->search_registration_class) {

            $search = $request->search_registration_class;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%")
                ->orWhere('kota', 'like', "%{$search}%");

            });
        }
        // SORT
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $columns = $request->input('columns');

        $orderColumn = $columns[$orderColumnIndex]['data'] ?? 'created_at';

        $allowedColumns = [
            'name',
            'venue',
            'created_at',
        ];

        if (!in_array($orderColumn, $allowedColumns)) {
            $orderColumn = 'created_at';
        }

        $query->orderBy($orderColumn, $orderDirection);

        $recordsTotal = RegistrationOriginal::count();
        $recordsFiltered = (clone $query)->count();

        $eventId = $query->clone()->first()?->event_id;

        $duplicateNumbers = [];

        if ($eventId) {
            // 2. Cari racer_number dari tabel registrations yang dipakai oleh lebih dari 1 racer_id berbeda pada event ini
            $duplicateNumbers = \DB::table('registrations_originals')
                ->join('registrations', 'registrations_originals.registration_id', '=', 'registrations.id')
                ->where('registrations_originals.event_id', $eventId)
                ->whereNotNull('registrations.racer_number') // Diubah ke registrations
                ->groupBy('registrations.racer_number')      // Diubah ke registrations
                ->havingRaw('COUNT(DISTINCT registrations.racer_id) > 1')
                ->pluck('registrations.racer_number')        // Diubah ke registrations
                ->toArray();
        }

        $registrationOriginals = $query
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($registOriginal) use ($duplicateNumbers) {

                $registration = $registOriginal->registration;
                $racer = $registration?->racer;
                $event = $registOriginal->event;
                $eventClass = $registOriginal->eventClass;
                $isDuplicateNumber = in_array($registration->racer_number, $duplicateNumbers);

                return [

                    'id' => $registOriginal->id,
                    'invoice_number' => $registOriginal->invoice_number,
                    'racer_number' => $registOriginal->racer_number,
                    'is_racer_number_duplicate' => $isDuplicateNumber,
                    'vehicle' => $registOriginal->vehicle,
                    'vehicle_number' => $registOriginal->vehicle_number,
                    'rangka_number' => $registOriginal->rangka_number,
                    'created_at' => $registOriginal->created_at?->format('Y-m-d H:i'),
                    'team_name' => $registOriginal->team_name,

                    'racer' => $racer ? [
                        'id' => $racer->id,
                        'nik' => $racer->nik,
                        'full_name' => $racer->full_name,
                        'short_name' => $racer->short_name,
                        'phone_number' => $racer->phone_number,
                        'birth_location' => $racer->birth_location,
                        'racer_number' => $racer->racer_number,
                        'birth_date' => $racer->birth_date
                            ? Carbon::parse($racer->birth_date)->translatedFormat('d F Y')
                            : null,
                        'hometown' => $racer->hometown,
                        'photo' => $racer->photo
                            ? asset('storage/' . $racer->photo)
                            : null,
                        'kta' => $racer->kta
                            ? asset('storage/' . $racer->kta)
                            : null,
                        'kis' => $racer->kis
                            ? asset('storage/' . $racer->kis)
                            : null,
                        'is_photo' => $racer->photo
                            ? true
                            : false,
                        'is_kta' => $racer->kta
                            ? true
                            : false,
                        'is_kis' => $racer->kis
                            ? true
                            : false,
                        'user_name' => $racer->user?->name,
                        'user_phone_number' => $racer->user?->phone_number,
                    ] : null,
                    'registration' => $registration,
                    'event' => $event ? [
                            'id' => $event->id,
                            'name' => $event->name,
                        ] : null,
                    'event_class' => $eventClass ? [
                        'id' => $eventClass->id,
                        'name' => $eventClass->name,
                    ] : null,
                ];
            });

        // dd($registrationOriginals); // debug
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $registrationOriginals
        ]);
    }

    /**
     * Update registration class & parent registration data
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $registrationClass = RegistrationClass::with('registration')->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'racer_number'   => 'nullable|string|max:50',
                'vehicle'        => 'nullable|string|max:255',
                'vehicle_number' => 'nullable|string|max:100',
                'rangka_number'  => 'nullable|string|max:100',
            ], [
                'racer_number.max'     => 'Nomor start maksimal terdiri dari 50 karakter.',
                'vehicle.max'          => 'Nama kendaraan maksimal terdiri dari 255 karakter.',
                'vehicle_number.max'   => 'Nomor mesin maksimal terdiri dari 100 karakter.',
                'rangka_number.max'    => 'Nomor rangka maksimal terdiri dari 100 karakter.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors()
                ], 422);
            }

            DB::transaction(function () use ($request, $registrationClass) {

                $registrationClass->vehicle        = $request->vehicle;
                $registrationClass->vehicle_number = $request->vehicle_number;
                $registrationClass->rangka_number  = $request->rangka_number;

                if ($request->has('racer_number')) {
                    $registrationClass->racer_number = $request->racer_number;
                }
                $registrationClass->save();

                $registration = $registrationClass->registration;
                if ($registration) {
                    if ($request->has('racer_number')) {
                        $registration->racer_number = $request->racer_number;
                    }
                    $registration->save();
                }
            });

            $registrationClass->refresh()->load('registration');

            return response()->json([
                'success' => true,
                'message' => 'Data race dan kendaraan berhasil diperbarui',
                'data'    => $registrationClass
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftaran kelas tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
