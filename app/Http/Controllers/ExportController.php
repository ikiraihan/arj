<?php

namespace App\Http\Controllers;

use App\Exports\RegistrationClassExport;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Support\RegistrationClassFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.export.index', compact('events'));
    }

    public function previewRace(Request $request, $eventId)
    {
        $filters = array_merge($request->all(), ['event_id' => $eventId]);

        $query = RegistrationClassFilter::apply($filters);
        $total = $query->count();

        $data = $query->get()->map(function ($registClass) {
            $registration = $registClass->registration;
            $racer = $registration?->racer;
            $event = $registClass->event;
            $eventClass = $registClass->eventClass;

            return [
                'invoice'              => $registration->registration_number,

                'nama_pembalap'        => $racer?->full_name,
                'nama_alias'           => $racer?->short_name,
                'nik'                  => $racer?->nik,
                'no_hp_pembalap'       => $racer?->phone_number,
                'tempat_lahir'         => $racer?->birth_location,
                'tanggal_lahir'        => $racer?->birth_date
                    ? Carbon::parse($racer->birth_date)->format('d-m-Y')
                    : null,
                'asal_kota'            => $racer?->hometown,
                'nomor_start'          => $registration?->racer_number,
                'nama_tim'             => $registration?->team_name,

                'nama_pendaftar'       => $registration?->name_register,
                'no_hp_pendaftar'      => $registration?->phone_number_register,

                'event'                => $event?->name,
                'kelas_event'          => $eventClass?->name,

                'kendaraan'            => $registClass->vehicle,
                'nomor_mesin'          => $registClass->vehicle_number,
                'nomor_rangka'         => $registClass->rangka_number,

                'status_balap'         => $registration?->race_status,
                'status_pembayaran'    => $registration?->status,
                'metode_pembayaran'    => $registration?->payment_method,

                'link_bukti_pembayaran' => $registration?->payment_proof
                    ? asset('storage/' . $registration->payment_proof)
                    : null,
                'ada_bukti_pembayaran' => $registration?->payment_proof ? 'Ya' : 'Tidak',

                'link_photo'           => $racer?->photo
                    ? asset('storage/' . $racer->photo)
                    : null,
                'ada_photo'            => $racer?->photo ? 'Ya' : 'Tidak',

                'link_kta'             => $racer?->kta
                    ? asset('storage/' . $racer->kta)
                    : null,
                'ada_kta'              => $racer?->kta ? 'Ya' : 'Tidak',

                'link_kis'             => $racer?->kis
                    ? asset('storage/' . $racer->kis)
                    : null,
                'ada_kis'              => $racer?->kis ? 'Ya' : 'Tidak',

                'tanggal_daftar'       => $registClass->created_at
                    ? Carbon::parse($registClass->created_at)->format('d-m-Y H:i')
                    : null,
            ];
        });

        return response()->json([
            'total'         => $total,
            'preview_count' => $data->count(),
            'headings'      => [
                'Invoice', 'Nama Pembalap', 'Nama Alias', 'NIK', 'No HP Pembalap',
                'Tempat Lahir', 'Tanggal Lahir', 'Asal Kota', 'Nomor Start', 'Nama Tim',
                'Nama Pendaftar', 'No HP Pendaftar',
                'Event', 'Kelas Event',
                'Kendaraan', 'Nomor Mesin', 'Nomor Rangka',
                'Status Balap', 'Status Pembayaran', 'Metode Pembayaran',
                'Link Bukti Pembayaran', 'Ada Bukti Pembayaran',
                'Link Photo', 'Ada Photo',
                'Link KTA', 'Ada KTA',
                'Link KIS', 'Ada KIS',
                'Tanggal Daftar',
            ],
            'data' => $data,
        ]);
    }

    public function exportRace(Request $request, $eventId)
    {
        $filters = array_merge($request->all(), ['event_id' => $eventId]);

        return Excel::download(
            new RegistrationClassExport($filters),
            'registrasi-balap-' . $eventId . '-' . now()->format('YmdHis') . '.xlsx'
        );
    }

    // public function exportRace(Request $request, $eventId)
    // {
    //     switch ($request->type) {
    //         case 'original':
    //             return Excel::download(
    //                 new RegistrationClassExport(array_merge($request->all(), ['event_id' => $eventId])),
    //                 'registrasi-balap-original-'. $eventId . '-' . now()->format('YmdHis') . '.xlsx'
    //             );
    //         default:
    //             return Excel::download(
    //                 new RegistrationClassExport(array_merge($request->all(), ['event_id' => $eventId])),
    //                 'registrasi-balap-'. $eventId . '-' . now()->format('YmdHis') . '.xlsx'
    //             );
    //     }
    // }
}

