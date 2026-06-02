<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\EventPhoto;
use App\Models\EventContactPerson;
use App\Models\EventPaymentAccount;
use App\Models\Racer;
use App\Models\Registration;
use App\Models\RegistrationClass;
use App\Models\RegistrationOriginal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Get all events
     */
    public function index(Request $request): JsonResponse
    {
        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = Event::select([
            'id',
            'name',
            'venue',
            'type',
            'provinsi',
            'kota',
            'link_maps',
            'is_active',
            'registration_start_date',
            'registration_end_date',
            'start_date',
            'end_date',
            'created_at'
        ])
        ->withCount('registrations')
        ->with([
            'contacts',
            'paymentAccount',
            'photos'
        ]);

        if ($request->type == 'grid') {
            $query->where('is_active', true);
        }

        // SEARCH
        if ($request->search_event) {

            $search = $request->search_event;

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

        $recordsTotal = Event::count();
        $recordsFiltered = (clone $query)->count();

        $events = $query
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($event) {

                return [

                    'id' => $event->id,
                    'name' => $event->name,
                    'venue' => $event->venue,
                    'kota' => $event->kota,
                    'provinsi' => $event->provinsi,
                    'location' => $event->venue . ', ' . $event->kota . ', ' . $event->provinsi,
                    'link_maps' => $event->link_maps,
                    'is_active' => $event->is_active,
                    'link_documentation' => $event->link_documentation,
                    'link_documentation_active' => $event->link_documentation_active,
                    'type' => $event->type,
                    // 'registration_date' =>  $event->registration_end_date && $event->registration_start_date ?  Carbon::parse($event->registration_start_date)->format('Y-m-d')    . ' - ' .    Carbon::parse($event->registration_end_date)->format('Y-m-d') : null,
                    'registration_date_formatted' =>  $event->registration_end_date && $event->registration_start_date ?  Carbon::parse($event->registration_start_date)->translatedFormat('d F Y H:i')    . ' - ' .    Carbon::parse($event->registration_end_date)->translatedFormat('d F Y H:i') : null,
                    'registration_end_date_formatted' => $event->registration_end_date ? Carbon::parse($event->registration_end_date)->translatedFormat('d F Y H:i') : null,
                    // 'event_date' =>    Carbon::parse($event->start_date)->format('Y-m-d')    . ' - ' .    Carbon::parse($event->end_date)->format('Y-m-d'),
                    'event_date_formatted' =>
                        ($event->start_date && $event->end_date)
                            ? Carbon::parse($event->start_date)->translatedFormat('d F Y')
                                . ' - ' .
                            Carbon::parse($event->end_date)->translatedFormat('d F Y')
                            : null,
                    'created_at' => $event->created_at?->format('Y-m-d H:i'),
                    'registrants' => $event->registrations_count,

                    'registration_start_date' => $event->registration_start_date ? Carbon::parse($event->registration_start_date)->format('Y-m-d\TH:i') : null,
            'registration_end_date' =>  $event->registration_end_date ?  Carbon::parse($event->registration_end_date)->format('Y-m-d\TH:i') : null,
                    'start_date' =>  $event->start_date ?  Carbon::parse($event->start_date)->format('Y-m-d') : null,
                    'end_date' =>  $event->end_date ?  Carbon::parse($event->end_date)->format('Y-m-d') : null,


                    'contacts' => $event->contacts->map(function ($contact) {
                        return [
                            'id' => $contact->id,
                            'name' => $contact->name,
                            'phone_number' => $contact->phone_number,
                        ];
                    }),

                    'payment_account' => $event->paymentAccount ? [
                        'id' => $event->paymentAccount->id,
                        'bank_name' => $event->paymentAccount->bank_name,
                        'account_number' => $event->paymentAccount->account_number,
                        'account_holder_name' => $event->paymentAccount->account_holder_name,
                    ] : null,

                    'photos' => $event->photos->map(function ($photo) {
                        return [
                            'id' => $photo->id,
                            'path' => $photo->path,
                            'url' => asset('storage/' . $photo->path),
                        ];
                    }),

                ];
            });
        // dd($events); // debug
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $events
        ]);
    }

    /**
     * Show event detail
     */
    public function show($id): JsonResponse
    {
        $event = Event::with([
                'contacts',
                'paymentAccount',
                'photos'
            ])
            ->withCount('registrations')
            ->findOrFail($id);

        $data = [

            'id' => $event->id,
            'name' => $event->name,
            'venue' => $event->venue,
            'kota' => $event->kota,
            'provinsi' => $event->provinsi,
            'description' => $event->description,
            'type' => $event->type,

            'location' =>
                $event->venue . ', ' .
                $event->kota . ', ' .
                $event->provinsi,

            'link_maps' => $event->link_maps,

            'is_active' => $event->is_active,

            'link_documentation' => $event->link_documentation,

            'link_documentation_active' => $event->link_documentation_active,

            'registration_date_formatted' =>
                ($event->registration_start_date && $event->registration_end_date)
                    ? Carbon::parse($event->registration_start_date)->translatedFormat('d F Y H:i')
                        . ' - ' .
                    Carbon::parse($event->registration_end_date)->translatedFormat('d F Y H:i')
                    : null,

            'registration_end_date_formatted' =>
                $event->registration_end_date
                    ? Carbon::parse($event->registration_end_date)->translatedFormat('d F Y H:i')
                    : null,

            'event_date_formatted' =>
                ($event->start_date && $event->end_date)
                    ? Carbon::parse($event->start_date)->translatedFormat('d F Y')
                        . ' - ' .
                    Carbon::parse($event->end_date)->translatedFormat('d F Y')
                    : null,

            'registration_start_date' => $event->registration_start_date ? Carbon::parse($event->registration_start_date)->format('Y-m-d\TH:i') : null,
            'registration_end_date' =>  $event->registration_end_date ?  Carbon::parse($event->registration_end_date)->format('Y-m-d\TH:i') : null,
            'start_date' =>  $event->start_date ?  Carbon::parse($event->start_date)->format('Y-m-d') : null,
            'end_date' =>  $event->end_date ?  Carbon::parse($event->end_date)->format('Y-m-d') : null,

            'created_at' =>
                $event->created_at?->format('Y-m-d H:i'),

            'registrants' =>
                $event->registrations_count,

            'contacts' => $event->contacts->map(function ($contact) {

                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'phone_number' => $contact->phone_number,
                ];

            }),

            'payment_account' => $event->paymentAccount ? [

                'id' => $event->paymentAccount->id,
                'bank_name' => $event->paymentAccount->bank_name,
                'account_number' => $event->paymentAccount->account_number,
                'account_holder_name' => $event->paymentAccount->account_holder_name,

            ] : null,

            'photos' => $event->photos->map(function ($photo) {

                return [
                    'id' => $photo->id,
                    'path' => $photo->path,
                    'url' => asset('storage/' . $photo->path),
                ];

            }),

        ];

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
    /**
     * Update Event
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {

            $event = Event::findOrFail($id);

            $validated = $request->validate([

                /*
                |--------------------------------------------------------------------------
                | EVENT
                |--------------------------------------------------------------------------
                */

                'name' => 'required|string|max:255',
                'venue' => 'required|string|max:255',
                'is_active' => 'nullable|boolean',
                'type' => 'required|string',

                'provinsi' => 'nullable|string|max:100',
                'kota' => 'nullable|string|max:100',

                'link_maps' => 'nullable|url',

                'registration_start_date' => 'required|date_format:Y-m-d\TH:i',
                'registration_end_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:registration_start_date',

                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',

                'description' => 'nullable|string',

                'link_documentation' => 'nullable|url',
                'link_documentation_active' => 'nullable|boolean',

                /*
                |--------------------------------------------------------------------------
                | IMAGES
                |--------------------------------------------------------------------------
                */

                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',

                /*
                |--------------------------------------------------------------------------
                | CONTACT PERSONS
                |--------------------------------------------------------------------------
                */

                'contacts' => 'nullable|array',

                'contacts.*.name' => 'required_with:contacts|string|max:255',
                'contacts.*.phone_number' => 'required_with:contacts|string|max:20',

                /*
                |--------------------------------------------------------------------------
                | PAYMENT ACCOUNT
                |--------------------------------------------------------------------------
                */

                'payment_account.bank_name' => 'nullable|string|max:100',
                'payment_account.account_number' => 'nullable|string|max:100',
                'payment_account.account_holder_name' => 'nullable|string|max:255',

            ], [

                'name.required' => 'Nama event wajib diisi',
                'venue.required' => 'Venue wajib diisi',
                'type.required' => 'Tipe wajib diisi',

                'registration_start_date.required' => 'Tanggal registrasi mulai wajib diisi',
                'registration_end_date.required' => 'Tanggal registrasi selesai wajib diisi',
                'registration_end_date.after_or_equal' => 'Tanggal registrasi selesai harus setelah tanggal mulai',

                'start_date.required' => 'Tanggal event mulai wajib diisi',
                'end_date.required' => 'Tanggal event selesai wajib diisi',
                'end_date.after_or_equal' => 'Tanggal event selesai harus setelah tanggal mulai',

                'contacts.*.name.required_with' => 'Nama contact person wajib diisi',
                'contacts.*.phone_number.required_with' => 'Nomor telepon contact person wajib diisi',

                'images.*.image' => 'File foto harus berupa gambar.',
                'images.*.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
                'images.*.max' => 'Ukuran foto maksimal 2 MB.',

            ]);

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | UPDATE EVENT
            |--------------------------------------------------------------------------
            */

            $event->update([
                'name' => $validated['name'],
                'venue' => $validated['venue'],
                'type' => $validated['type'],

                'provinsi' => $validated['provinsi'] ?? null,
                'kota' => $validated['kota'] ?? null,

                'link_maps' => $validated['link_maps'] ?? null,

                'registration_start_date' => $validated['registration_start_date'],
                'registration_end_date' => $validated['registration_end_date'],

                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],

                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? false,

                'link_documentation' => $validated['link_documentation'] ?? null,
                'link_documentation_active' => $validated['link_documentation_active'] ?? false,
            ]);

            /*
            |--------------------------------------------------------------------------
            | STORE NEW PHOTOS
            |--------------------------------------------------------------------------
            */

            if ($request->deleted_images) {

                $deletedIds = json_decode($request->deleted_images, true);

                $photos = EventPhoto::whereIn('id', $deletedIds)->get();

                foreach ($photos as $photo) {
                    Storage::disk('public')->delete($photo->path);
                }

                EventPhoto::whereIn('id', $deletedIds)->delete();
            }

            if ($request->hasFile('images')) {

                $lastPosition = EventPhoto::where('event_id', $event->id)
                    ->max('position') ?? 0;

                foreach ($request->file('images') as $index => $file) {

                    $filename = 'event_' . $event->id . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();

                    $path = $file->storeAs('events', $filename, 'public');

                    EventPhoto::create([
                        'event_id' => $event->id,
                        'path' => $path,
                        'position' => $lastPosition + $index + 1
                    ]);
                }
            }
            /*
            |--------------------------------------------------------------------------
            | REPLACE CONTACT PERSONS
            |--------------------------------------------------------------------------
            */

            EventContactPerson::where('event_id', $event->id)->delete();

            if (!empty($validated['contacts'])) {

                foreach ($validated['contacts'] as $contact) {

                    EventContactPerson::create([
                        'event_id' => $event->id,
                        'name' => $contact['name'],
                        'phone_number' => $contact['phone_number'],
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE PAYMENT ACCOUNT
            |--------------------------------------------------------------------------
            */

            if (
                !empty($validated['payment_account']['bank_name']) ||
                !empty($validated['payment_account']['account_number']) ||
                !empty($validated['payment_account']['account_holder_name'])
            ) {

                EventPaymentAccount::updateOrCreate(
                    [
                        'event_id' => $event->id
                    ],
                    [
                        'bank_name' => $validated['payment_account']['bank_name'] ?? null,
                        'account_number' => $validated['payment_account']['account_number'] ?? null,
                        'account_holder_name' => $validated['payment_account']['account_holder_name'] ?? null,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event berhasil diupdate',
                'data' => $event
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Gagal update event: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate event'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return response()->json([
            'status' => true,
            'message' => 'Event berhasil dihapus'
        ]);
    }

    /**
     * Store Registration Event
     */
    public function storeFormRegistration(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::find(Auth::id());

            $validated = $request->validate([

                'event_id' => 'required|exists:events,id',
                'racer_id' => 'required',
                'racer_full_name' => 'required_if:racer_id,new|nullable|string|max:255',
                'racer_short_name' => 'required_if:racer_id,new|nullable|string|max:255',
                'racer_nik' => 'required_if:racer_id,new|nullable|string|max:50',
                'racer_phone_number' => 'nullable|string|max:20',
                'racer_number' => 'required_if:racer_id,new|max:20',
                'racer_birth_location' => 'required_if:racer_id,new|nullable|string|max:255',
                'racer_birth_date' => 'required_if:racer_id,new|nullable|date',
                'racer_hometown' => 'required_if:racer_id,new|nullable|string|max:255',
                'phone_number_register' => 'required|string|max:20',
                'name_register' => 'required|string',
                'event_class_id' => 'required|array|min:1',
                'event_class_id.*' => 'required|exists:event_classes,id',

                'class_detail' => 'required|array',
                'class_detail.*.start_number' => 'nullable|string',
                'class_detail.*.vehicle' => 'nullable|string|max:255',
                'class_detail.*.engine_number' => 'nullable|string|max:100',
                'class_detail.*.frame_number' => 'nullable|string|max:100',

                'payment_method' => 'required|in:tunai,transfer',
                'team_name' => 'required',

            ], [

                'event_id.required' => 'Event wajib dipilih',
                'event_id.exists' => 'Event tidak ditemukan',
                'racer_id.required' => 'Data pembalap wajib diisi',

                'racer_full_name.required_if' => 'Nama lengkap pembalap wajib diisi jika menambah pembalap baru',
                'racer_full_name.string' => 'Nama lengkap pembalap harus berupa teks',
                'racer_full_name.max' => 'Nama lengkap pembalap maksimal 255 karakter',

                'racer_short_name.required_if' => 'Nama pendek pembalap wajib diisi jika menambah pembalap baru',
                'racer_short_name.string' => 'Nama pendek pembalap harus berupa teks',
                'racer_short_name.max' => 'Nama pendek pembalap maksimal 255 karakter',

                'racer_nik.required_if' => 'NIK pembalap wajib diisi jika menambah pembalap baru',
                'racer_nik.string' => 'NIK pembalap harus berupa teks',
                'racer_nik.max' => 'NIK pembalap maksimal 50 karakter',

                'racer_phone_number.required_if' => 'Nomor HP pembalap wajib diisi jika menambah pembalap baru',
                'racer_phone_number.string' => 'Nomor HP pembalap harus berupa teks',
                'racer_phone_number.max' => 'Nomor HP pembalap maksimal 20 karakter',

                'racer_number.required_if' => 'Nomor Start pembalap wajib diisi jika menambah pembalap baru',
                'racer_number.max' => 'Nomor Start pembalap maksimal 20 karakter',

                'racer_birth_location.required_if' => 'Tempat lahir pembalap wajib diisi jika menambah pembalap baru',
                'racer_birth_location.string' => 'Tempat lahir pembalap harus berupa teks',
                'racer_birth_location.max' => 'Tempat lahir pembalap maksimal 255 karakter',

                'racer_birth_date.required_if' => 'Tanggal lahir pembalap wajib diisi jika menambah pembalap baru',
                'racer_birth_date.date' => 'Tanggal lahir pembalap tidak valid',

                'racer_hometown.required_if' => 'Asal kota pembalap wajib diisi jika menambah pembalap baru',
                'racer_hometown.string' => 'Asal kota pembalap harus berupa teks',
                'racer_hometown.max' => 'Asal kota pembalap maksimal 255 karakter',

                'racer_photo' => 'required_if:racer_id,new|nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
                'racer_kta' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'racer_kis' => 'required_if:racer_id,new|nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',

                'phone_number.required' => 'Nomor telepon wajib diisi',
                'phone_number.string' => 'Nomor telepon harus berupa teks',
                'phone_number.max' => 'Nomor telepon maksimal 20 karakter',

                'event_class_id.required' => 'Kelas event wajib dipilih minimal 1',
                'event_class_id.array' => 'Format kelas event tidak valid',
                'event_class_id.min' => 'Minimal pilih satu kelas event',
                'event_class_id.*.required' => 'Kelas event wajib dipilih',
                'event_class_id.*.exists' => 'Kelas event tidak ditemukan',

                'class_detail.required' => 'Detail kelas wajib diisi',
                'class_detail.array' => 'Format detail kelas tidak valid',
                'class_detail.*.start_number.string' => 'Nomor start harus berupa teks',
                'class_detail.*.start_number.max' => 'Nomor start maksimal 20 karakter',
                'class_detail.*.vehicle.string' => 'Nama kendaraan harus berupa teks',
                'class_detail.*.vehicle.max' => 'Nama kendaraan maksimal 255 karakter',
                'class_detail.*.engine_number.string' => 'Nomor mesin harus berupa teks',
                'class_detail.*.engine_number.max' => 'Nomor mesin maksimal 100 karakter',
                'class_detail.*.frame_number.string' => 'Nomor rangka harus berupa teks',
                'class_detail.*.frame_number.max' => 'Nomor rangka maksimal 100 karakter',

                'payment_method.required' => 'Metode pembayaran wajib dipilih',
                'payment_method.in' => 'Metode pembayaran tidak valid',

                'team_name.required' => 'Nama Team wajib dipilih',

            ]);

            /**
             * ======================================
             * CREATE RACER IF NEW
             * ======================================
             */
            if ($request->racer_id === 'new') {

                $racer = Racer::create([

                    'user_id' => auth()->id(),
                    'nik' => $request->racer_nik,
                    'full_name' => $request->racer_full_name,
                    'short_name' => $request->racer_short_name,
                    'phone_number' => $request->racer_phone_number,
                    'birth_location' => $request->racer_birth_location,
                    'birth_date' => $request->racer_birth_date,
                    'hometown' => $request->racer_hometown,
                    'racer_number' => $request->racer_number,

                ]);

                // PHOTO
                if ($request->hasFile('racer_photo')) {

                    $racer->photo = $request
                        ->file('racer_photo')
                        ->store('racers/photo', 'public');

                }

                // KTA
                if ($request->hasFile('racer_kta')) {

                    $racer->kta = $request
                        ->file('racer_kta')
                        ->store('racers/kta', 'public');

                }

                // KIS
                if ($request->hasFile('racer_kis')) {

                    $racer->kis = $request
                        ->file('racer_kis')
                        ->store('racers/kis', 'public');

                }

                $racer->save();

            } else {

                $racer = Racer::findOrFail($request->racer_id);

            }

            // dd($request->all()); // debug
            /**
             * ======================================
             * CREATE REGISTRATION
             * ======================================
             */
            $countRegist = Registration::where('event_id', $request->event_id)->withTrashed()->count();

            $event = Event::findOrFail($request->event_id);

            $isFined = false;

            if ($event->registration_end_date) {
                $isFined = Carbon::now()->gt(
                    Carbon::parse($event->registration_end_date)
                );
            }

            $registration = Registration::create([

                'event_id' => $request->event_id,
                'user_id' => Auth::id(),
                'racer_id' => $racer->id,
                'registration_number' => $countRegist + 1,
                'team_name' => $request->team_name,
                'phone_number_register' => $request->phone_number_register,
                'name_register' => $request->name_register,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method == 'transfer' ? 'menunggu-pembayaran' : 'unpaid',
                'race_status' => 'pending',
                'is_fined' => $isFined,
                'racer_number' => $event->type == 'drag' ? $countRegist + 1 : $racer->racer_number,
                // 'payment_method' => $request->payment_method,

            ]);

            $user->update([
                'name' => $request->name_register ?? $user->name,
                'phone_number' => $request->phone_number_register ?? $user->phone_number,
                'team_name'=> $request->team_name ?? $user->team_name,
            ]);
            /**
             * ======================================
             * CLASS DETAIL & ORIGINAL LOG
             * ======================================
             */
            $countRegistration = RegistrationClass::where('event_id', $request->event_id)->withTrashed()->count();

            foreach ($request->event_class_id as $classId) {
                $detail = $request->class_detail[$classId] ?? [];

                // 1. Ambil nomor invoice untuk baris kelas ini
                $currentInvoiceNumber = $countRegistration + 1;

                // 2. Simpan ke tabel operasional (RegistrationClass)
                RegistrationClass::create([
                    'event_id'        => $request->event_id,
                    'registration_id' => $registration->id,
                    'team_name'       => $request->team_name,
                    'invoice_number'  => $currentInvoiceNumber,
                    'class_id'        => $classId,
                    'racer_number'    => $detail['start_number'] ?? null,
                    'vehicle'         => $detail['vehicle'] ?? null,
                    'vehicle_number'  => $detail['engine_number'] ?? null,
                    'rangka_number'   => $detail['frame_number'] ?? null,
                ]);

                // 3. Simpan ke tabel log (RegistrationOriginal) untuk kelas ini
                RegistrationOriginal::create([
                    'registration_id'       => $registration->id,
                    'event_id'              => $request->event_id,
                    'racer_id'              => $racer->id,
                    'class_id'              => $classId,
                    'user_id'               => Auth::id(),
                    'registration_number'   => $registration->registration_number,
                    'team_name'             => $request->team_name,
                    'notes'                 => $request->notes ?? null,
                    'invoice_number'        => $currentInvoiceNumber, // Menggunakan invoice kelas saat ini
                    'vehicle'               => $detail['vehicle'] ?? null,
                    'vehicle_number'        => $detail['engine_number'] ?? null,
                    'rangka_number'         => $detail['frame_number'] ?? null,
                    'name_register'         => $request->name_register,
                    'phone_number_register' => $request->phone_number_register,
                    'racer_number'          => $event->type == 'drag' ? $countRegist + 1 : $racer->racer_number,
                    'payment_method'        => $request->payment_method,
                    'is_fined'              => $isFined,
                ]);

                $countRegistration++;
            }

            DB::commit();

            return response()->json([

                'success' => true,
                'message' => 'Registrasi berhasil',

            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()

            ], 422);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([

                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi event'

            ], 500);

        }
    }

    /**
     * Get all events
     */
    public function indexPendaftar(Request $request,$eventId): JsonResponse
    {
        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = Registration::where('event_id', $eventId)->select([
            'id',
            'racer_id',
            'registration_number',
            'user_id',
            'team_name',
            'status',
            'payment_proof',
            'payment_method',
            'race_status',
            'created_at',
            'is_fined',
            'name_register',
            'phone_number_register',
        ])->with([
            'registrationClasses.eventClass',
            'user',
            'racer'
        ]);
        // ->withCount('registrations')

        // SEARCH
        if ($request->search_pendaftar) {

            $search = $request->search_pendaftar;

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

        $recordsTotal = Registration::where('event_id', $eventId)->count();
        $recordsFiltered = (clone $query)->count();

        $registers = $query
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($register){
                $classes = $register->registrationClasses;
                $countClass = $classes->count();
                $totalPrice = $classes->sum(function ($registrationClass) use ($register) {

                    $price = $registrationClass->eventClass->price ?? 0;

                    if ($register->is_fined) {
                        $price += $registrationClass->eventClass->price_fine ?? 0;
                    }

                    return $price;
                });

                $racer = $register?->racer;

                return [
                    'id' => $register->id,
                    'racer_id' => $register->racer_id,
                    'registration_number' => $register->registration_number,
                    'user_id' => $register->user_id,
                    'team_name' => $register->team_name,
                    'name_register' => $register->name_register,
                    'phone_number_register' => $register->phone_number_register,
                    'status' => $register->status,
                    'race_status' => $register->race_status,
                    'payment_method' => $register->payment_method,
                    'payment_proof' => $register->payment_proof,
                    'payment_proof_url' => asset('storage/' . $register->payment_proof),
                    'count_class' => $countClass,
                    'total_price' => $totalPrice,
                    'created_at' => $register->created_at,
                    'user' => $register->user,
                    'is_fined' => $register->is_fined,
                    // 'racer' => $register->racer,
                    'classes' => $register->registrationClasses->map(function ($registrationClass) {
                        return [
                            'id' => $registrationClass->id,
                            'class_id' => $registrationClass->class_id,
                            'class_name' => $registrationClass->eventClass->name ?? null,
                            // 'price' => $registrationClass->eventClass->price ?? 0,
                            // 'price_fine' => $registrationClass->eventClass->price_fine ?? 0,
                            // 'racer_number' => $registrationClass->racer_number,
                            // 'vehicle' => $registrationClass->vehicle,
                            // 'vehicle_number' => $registrationClass->vehicle_number,
                            // 'rangka_number' => $registrationClass->rangka_number,
                        ];
                    }),
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
                    // 'classes' => $register->registrationClasses,
                ];
            });
        // dd($registers); // debug
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $registers
        ]);
    }

    public function approvalPayment(Request $request, $registrationId): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:paid,unpaid',
        ], [
            'status.required' => 'Status Pembayaran wajib dipilih',
            'status.in' => 'Status Pembayaran tidak valid',
        ]);

        $registration = Registration::findOrFail($registrationId);

        if($request->status == 'unpaid'){
            $status = $registration->payment_method == 'transfer' ? 'menunggu-pembayaran' : 'unpaid';
        }else{
            $status = $request->status;
        }


        // update registration
        $registration->update([
            'status' => $status,
            'race_status' => in_array($status,['paid']) ? 'approved' : $registration->race_status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Approval Berhasil dilakukan',
            // 'data' => [
            //     'payment_proof_url' => asset('storage/' . $path)
            // ]
        ]);
    }

    public function destroyRegistration($registrationId)
    {
        $registration = Registration::findOrFail($registrationId);

        $registration->delete();

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil dihapus'
        ]);
    }

    public function approvalRaceStatus(Request $request, $registrationId): JsonResponse
    {
        $request->validate([
            'race_status' => 'required|in:approved,rejected',
        ], [
            'race_status.required' => 'Status Balap wajib dipilih',
            'race_status.in' => 'Status Balap tidak valid',
        ]);

        $registration = Registration::findOrFail($registrationId);

        // update registration
        $registration->update([
            'race_status' => $request->race_status,
            'status' => $request->race_status == 'rejected' ? 'rejected' : $registration->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Approval Berhasil dilakukan',
            // 'data' => [
            //     'payment_proof_url' => asset('storage/' . $path)
            // ]
        ]);
    }

    public function updateFineStatus(Request $request, $registrationId)
    {
        $registration = Registration::findOrFail($registrationId);

        $status = $registration->is_fined == true ? false : true;
        $registration->update([
            'is_fined' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status denda berhasil diperbarui',
            'data' => $registration
        ]);
    }
}
