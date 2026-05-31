<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\EventPhoto;
use App\Models\EventContactPerson;
use App\Models\EventPaymentAccount;

class EventController extends Controller
{
    /**
     * Halaman Landing
     */
    public function index()
    {
        // $events = Event::all();
        return view('admin.event.index');
    }
    /**
     * Halaman Landing
     */
    public function indexList()
    {
        // $events = Event::all();

        return view('admin.event.index-list');
    }

    public function detail($id)
    {
        $event = Event::find($id);
        $eventId = $event->id;

        return view('admin.event.details', compact('event', 'eventId'));
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([

                // EVENT
                'name' => 'required|string|max:255',
                'venue' => 'required|string|max:255',
                'provinsi' => 'nullable|string|max:100',
                'kota' => 'nullable|string|max:100',
                'link_maps' => 'nullable|url',

                'type' => 'required|string',
                'registration_start_date' => 'required|date_format:Y-m-d\TH:i',
                'registration_end_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:registration_start_date',

                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',

                // 'is_active' => 'required|boolean',
                'description' => 'nullable|string',

                // IMAGES
                'images' => 'required|array|min:1',
                'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',


                // CONTACT PERSONS
                'contacts' => 'nullable|array',
                'contacts.*.name' => 'required_with:contacts|string|max:255',
                'contacts.*.phone_number' => 'required_with:contacts|string|max:20',

                // PAYMENT ACCOUNT
                'payment_account.bank_name' => 'nullable|string|max:100',
                'payment_account.account_number' => 'nullable|string|max:100',
                'payment_account.account_holder_name' => 'nullable|string|max:255',

                'is_active' => 'nullable|boolean',

                'link_documentation' => 'nullable|url',
                'link_documentation_active' => 'nullable|boolean',

            ], [

                // EVENT
                'name.required' => 'Nama event wajib diisi',
                'venue.required' => 'Venue wajib diisi',
                'type.required' => 'Tipe wajib diisi',

                'registration_start_date.required' => 'Tanggal registrasi mulai wajib diisi',
                'registration_end_date.required' => 'Tanggal registrasi selesai wajib diisi',
                'registration_end_date.after_or_equal' => 'Tanggal registrasi selesai harus setelah tanggal mulai',

                'start_date.required' => 'Tanggal event mulai wajib diisi',
                'end_date.required' => 'Tanggal event selesai wajib diisi',
                'end_date.after_or_equal' => 'Tanggal event selesai harus setelah tanggal mulai',

                // CONTACT
                'contacts.*.name.required_with' => 'Nama contact person wajib diisi',
                'contacts.*.phone_number.required_with' => 'Nomor telepon contact person wajib diisi',

                'images.*.image' => 'File foto harus berupa gambar.',
                'images.*.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
                'images.*.max' => 'Ukuran foto maksimal 2 MB.',

            ]);

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | CREATE EVENT
            |--------------------------------------------------------------------------
            */

            $event = Event::create([
                'name' => $validated['name'],
                'venue' => $validated['venue'],
                'provinsi' => $validated['provinsi'] ?? null,
                'kota' => $validated['kota'] ?? null,
                'link_maps' => $validated['link_maps'] ?? null,
                'type' => $validated['type'] ?? null,
                'is_active' => $validated['is_active'] ?? false,

                'registration_start_date' => $validated['registration_start_date'],
                'registration_end_date' => $validated['registration_end_date'],

                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],

                // 'is_active' => $validated['is_active'],
                'description' => $validated['description'] ?? null,

                'link_documentation' => $validated['link_documentation'] ?? null,
                'link_documentation_active' => $validated['link_documentation_active'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | STORE EVENT PHOTOS
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $index => $file) {

                    $filename = 'event_' . $event->id . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();

                    $path = $file->storeAs('events', $filename, 'public');

                    EventPhoto::create([
                        'event_id' => $event->id,
                        'path' => $path,
                        'position' => $index + 1
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | STORE CONTACT PERSONS
            |--------------------------------------------------------------------------
            */

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
            | STORE PAYMENT ACCOUNT
            |--------------------------------------------------------------------------
            */

            if (
                !empty($validated['payment_account']['bank_name']) ||
                !empty($validated['payment_account']['account_number']) ||
                !empty($validated['payment_account']['account_holder_name'])
            ) {

                EventPaymentAccount::create([
                    'event_id' => $event->id,
                    'bank_name' => $validated['payment_account']['bank_name'] ?? null,
                    'account_number' => $validated['payment_account']['account_number'] ?? null,
                    'account_holder_name' => $validated['payment_account']['account_holder_name'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Event berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('open_offcanvas', 'offcanvas_add');

        } catch (\Exception $e) {
            // dd([
            //     'message' => $e->getMessage(),
            //     'file' => $e->getFile(),
            //     'line' => $e->getLine(),
            // ]);
            DB::rollBack();

            Log::error('Gagal store event: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan event')
                ->withInput()
                ->with('open_offcanvas', 'offcanvas_add');
        }
    }

}
