<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventClass;
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
            if ($request->search_race) {

                $search = $request->search_race;

                $query->where(function ($q) use ($search) {

                    $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('racer_number', 'like', "%{$search}%")
                    ->orWhere('vehicle', 'like', "%{$search}%")
                    ->orWhereHas('registration', function ($registration) use ($search) {
                        $registration->where('team_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('registration.racer', function ($racer) use ($search) {
                        $racer->where('full_name', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });

                });
            }

            // NO KWITANSI
            $query->when($request->race_receipt_number, function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->race_receipt_number . '%');
            });

            // NAMA PEMBALAP
            $query->when($request->race_racer_name, function ($q) use ($request) {
                $q->whereHas('registration.racer', function ($racer) use ($request) {
                    $racer->where('full_name', 'like', '%' . $request->race_racer_name . '%')
                        ->orWhere('short_name', 'like', '%' . $request->race_racer_name . '%');
                });
            });

            // NIK
            $query->when($request->race_nik, function ($q) use ($request) {
                $q->whereHas('registration.racer', function ($racer) use ($request) {
                    $racer->where('nik', 'like', '%' . $request->race_nik . '%');
                });
            });

            // NO START
            $query->when($request->race_racer_number, fn ($q) =>
                $q->whereHas('registration', fn ($registration) =>
                    $registration->where(
                        'racer_number',
                        'like',
                        '%' . $request->race_racer_number . '%'
                    )
                )
            );

            // TEAM
            $query->when($request->race_team_name, function ($q) use ($request) {
                $q->whereHas('registration', function ($registration) use ($request) {
                    $registration->where('team_name', 'like', '%' . $request->race_team_name . '%');
                });
            });

            // KOTA
            $query->when($request->race_city, function ($q) use ($request) {
                $q->whereHas('registration.racer', function ($racer) use ($request) {
                    $racer->where('hometown', 'like', '%' . $request->race_city . '%');
                });
            });

            // KELAS
            $query->when($request->race_class_name, function ($q) use ($request) {
                $q->whereHas('eventClass', function ($class) use ($request) {
                    $class->where('name', 'like', '%' . $request->race_class_name . '%');
                });
            });

            // KENDARAAN
            $query->when($request->race_vehicle, function ($q) use ($request) {
                $q->where('vehicle', 'like', '%' . $request->race_vehicle . '%');
            });

            // NO RANGKA
            $query->when($request->race_chassis_number, function ($q) use ($request) {
                $q->where('rangka_number', 'like', '%' . $request->race_chassis_number . '%');
            });

            // NO MESIN
            $query->when($request->race_engine_number, function ($q) use ($request) {
                $q->where('vehicle_number', 'like', '%' . $request->race_engine_number . '%');
            });

            // FOTO
            $query->when($request->race_has_photo !== null && $request->race_has_photo !== '', function ($q) use ($request) {

                if ($request->race_has_photo == '1') {
                    $q->whereHas('registration.racer', fn($r) => $r->whereNotNull('photo'));
                } else {
                    $q->whereHas('registration.racer', fn($r) => $r->whereNull('photo'));
                }
            });

            // KIS
            $query->when($request->race_has_kis !== null && $request->race_has_kis !== '', function ($q) use ($request) {

                if ($request->race_has_kis == '1') {
                    $q->whereHas('registration.racer', fn($r) => $r->whereNotNull('kis'));
                } else {
                    $q->whereHas('registration.racer', fn($r) => $r->whereNull('kis'));
                }
            });

            // KTA
            $query->when($request->race_has_kta !== null && $request->race_has_kta !== '', function ($q) use ($request) {

                if ($request->race_has_kta == '1') {
                    $q->whereHas('registration.racer', fn($r) => $r->whereNotNull('kta'));
                } else {
                    $q->whereHas('registration.racer', fn($r) => $r->whereNull('kta'));
                }
            });
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

            $duplicateNumberQuery = DB::table('registration_classes')
            ->join('registrations', 'registration_classes.registration_id', '=', 'registrations.id')
            ->where('registration_classes.event_id', $eventId)
            ->where('registrations.race_status', 'approved')
            ->whereNotNull('registration_classes.racer_number')
            ->groupBy('registration_classes.racer_number')
            ->havingRaw('COUNT(DISTINCT registrations.racer_id) > 1')
            ->select('registration_classes.racer_number');

            $query->when(
                $request->race_racer_number_duplicate === 'duplicate',
                function ($q) use ($duplicateNumberQuery) {

                    $q->whereIn(
                        'racer_number',
                        $duplicateNumberQuery
                    );
                }
            );

            $query->when(
                $request->race_racer_number_duplicate === 'unique',
                function ($q) use ($duplicateNumberQuery) {

                    $q->whereNotIn(
                        'racer_number',
                        $duplicateNumberQuery
                    );
                }
            );

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

    public function summary(Request $request, $eventId): JsonResponse
    {
        $registrationClasses = RegistrationClass::query()
            ->where('event_id', $eventId)
            ->whereHas('registration', function ($q) use ($request) {
                $q->withoutTrashed()
                    ->where('race_status', 'approved');

                if ($request->filled('payment_status')) {

                    $statuses = match ($request->payment_status) {
                        'paid' => ['paid'],
                        'unpaid' => ['unpaid', 'menunggu-pembayaran', 'menunggu-approval'],
                        default => []
                    };

                    if (!empty($statuses)) {
                        $q->whereIn('status', $statuses);
                    }
                }
            })
            ->with([
                'eventClass:id,name,price',
                'registration:id,is_fined'
            ])
            ->get();

            $eventClasses = EventClass::where('event_id', $eventId)
                ->select('id', 'name', 'price', 'price_fine')
                ->get();
            $summary = $eventClasses->map(function ($eventClass) use ($registrationClasses) {
                $classRegistrations = $registrationClasses
                    ->where('class_id', $eventClass->id);

                $participantCount = $classRegistrations->count();

                $fineCount = $classRegistrations->filter(function ($item) {
                    return $item->registration?->is_fined == 1;
                })->count();

                $price = $eventClass->price ?? 0;
                $priceFine = $eventClass->price_fine ?? 0;

                $fineTotal = $fineCount * $priceFine;

                $totalWithoutFines = $participantCount * $price;

                $totalIncome = $totalWithoutFines + $fineTotal;

                return [
                    'class_id' => $eventClass->id,
                    'class_name' => $eventClass->name,
                    'participant_count' => $participantCount,

                    'price' => $price,
                    'price_fine' => $priceFine,

                    'total_without_fines' => $totalWithoutFines,

                    'fine_count' => $fineCount,
                    'fine_total' => $fineTotal,

                    'total_income' => $totalIncome,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $summary,
            'summary' => [
                'total_participants' => $summary->sum('participant_count'),
                'total_fines' => $summary->sum('fine_count'),
                'total_fine_amount' => $summary->sum('fine_total'),
                'grand_total' => $summary->sum('total_income'),
                'total_without_fines' => $summary->sum('total_without_fines'),
            ]
        ]);
    }

    public function reportIncomePayment(Request $request, $eventId): JsonResponse
    {
        $registrationClasses = RegistrationClass::query()
            ->where('event_id', $eventId)
            ->whereHas('registration', function ($q) use ($request) {

                $q->withoutTrashed()
                    ->where('race_status', 'approved');

                if ($request->filled('payment_status')) {

                    $statuses = match ($request->payment_status) {
                        'paid' => ['paid'],
                        'unpaid' => ['unpaid', 'menunggu-pembayaran', 'menunggu-approval'],
                        default => []
                    };

                    if (!empty($statuses)) {
                        $q->whereIn('status', $statuses);
                    }
                }
            })
            ->with([
                'eventClass:id,name,price,price_fine',
                'registration:id,is_fined,payment_method'
            ])
            ->get();

        $data = $registrationClasses
            ->groupBy(function ($item) {
                return $item->registration?->payment_method ?? 'Tidak Diketahui';
            })
            ->map(function ($items, $method) {

                $transactionCount = $items->count();

                $totalIncome = $items->sum(function ($item) {

                    $price = $item->eventClass?->price ?? 0;

                    $fine = 0;

                    if ($item->registration?->is_fined) {
                        $fine = $item->eventClass?->price_fine ?? 0;
                    }

                    return $price + $fine;
                });

                return [
                    'payment_method' => ucfirst($method),
                    'transaction_count' => $transactionCount,
                    'total_income' => $totalIncome,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $data,
            'summary' => [
                'transaction_count' => $data->sum('transaction_count'),
                'grand_total' => $data->sum('total_income'),
            ]
        ]);
    }
}
