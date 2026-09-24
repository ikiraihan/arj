<?php

namespace App\Exports;

use App\Models\RegistrationClass;
use App\Support\RegistrationClassFilter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrationClassExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return RegistrationClassFilter::apply($this->filters)->get();
    }

    public function map($registClass): array
    {
        $registration = $registClass->registration;
        $racer = $registration?->racer;
        $event = $registClass->event;
        $eventClass = $registClass->eventClass;

        return [
            $registration->registration_number,

            $racer?->full_name,
            $racer?->short_name,
            $racer?->nik,
            $racer?->phone_number,
            $racer?->birth_location,
            $racer?->birth_date ? Carbon::parse($racer->birth_date)->format('d-m-Y') : null,
            $racer?->hometown,
            $registration?->racer_number,
            $registration?->team_name,

            $registration?->name_register,
            $registration?->phone_number_register,

            $event?->name,
            $eventClass?->name,

            $registClass->vehicle,
            $registClass->vehicle_number,
            $registClass->rangka_number,

            $registration?->race_status,
            $registration?->status,
            $registration?->payment_method,

            // PHOTO
            $registration?->payment_proof
                ? asset('storage/' . $registration->payment_proof)
                : null,
            $registration?->payment_proof ? 'Ya' : 'Tidak',

            // PHOTO
            $racer?->photo
                ? asset('storage/' . $racer->photo)
                : null,
            $racer?->photo ? 'Ya' : 'Tidak',

            // KTA
            $racer?->kta
                ? asset('storage/' . $racer->kta)
                : null,
            $racer?->kta ? 'Ya' : 'Tidak',

            // KIS
            $racer?->kis
                ? asset('storage/' . $racer->kis)
                : null,
            $racer?->kis ? 'Ya' : 'Tidak',

            $registClass->created_at
                ? Carbon::parse($registClass->created_at)->format('d-m-Y H:i')
                : null,
        ];
    }

    public function headings(): array
    {
        return [
            'Invoice',

            'Nama Pembalap',
            'Nama Alias',
            'NIK',
            'No HP Pembalap',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Asal Kota',
            'Nomor Start',
            'Nama Tim',

            'Nama Pendaftar',
            'No HP Pendaftar',

            'Event',
            'Kelas Event',

            'Kendaraan',
            'Nomor Mesin',
            'Nomor Rangka',

            'Status Balap',
            'Status Pembayaran',
            'Metode Pembayaran',

            'Link Bukti Pembayaran',
            'Ada Bukti Pembayaran',

            'Link Photo',
            'Ada Photo',

            'Link KTA',
            'Ada KTA',

            'Link KIS',
            'Ada KIS',

            'Tanggal Daftar',
        ];
    }
}
