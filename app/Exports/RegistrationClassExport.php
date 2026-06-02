<?php

namespace App\Exports;

use App\Models\RegistrationClass;
use Carbon\Carbon;
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
        $query = RegistrationClass::query()
            // ->whereHas('registration', function ($q) {
            //     $q->withoutTrashed()
            //     ->where('race_status', 'approved');
            // })
            ->with([
                'registration.racer.user',
                'event',
                'eventClass',
            ]);

        if (!empty($this->filters['event_id'])) {
            $query->where('event_id', $this->filters['event_id']);
        }

        if (!empty($this->filters['event_class_id'])) {
            $query->where('event_class_id', $this->filters['event_class_id']);
        }

        if (!empty($this->filters['search'])) {

            $search = $this->filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('racer_number', 'like', "%{$search}%")
                    ->orWhere('vehicle', 'like', "%{$search}%")
                    ->orWhereHas('registration.racer', function ($racer) use ($search) {
                        $racer->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->get();
    }

    public function map($registClass): array
    {
        $registration = $registClass->registration;
        $racer = $registration?->racer;
        $event = $registClass->event;
        $eventClass = $registClass->eventClass;

        return [
            $registClass->invoice_number,

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
