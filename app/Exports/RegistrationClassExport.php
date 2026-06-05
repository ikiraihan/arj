<?php

namespace App\Exports;

use App\Models\RegistrationClass;
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
        $query = RegistrationClass::query()
            ->whereHas('registration', function ($q) {
                $q->withoutTrashed()
                ->where('race_status', 'approved');
            })
            ->with([
                'registration.racer.user',
                'registration',
                'event',
                'eventClass',
            ]);

        $eventId = $this->filters['event_id'] ?? null;

        if ($eventId) {
            $query->where('event_id', $eventId);
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
                    ->orWhereHas('registration', function ($registration) use ($search) {
                        $registration->where('team_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('registration.racer', function ($racer) use ($search) {
                        $racer->where('full_name', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });

            });
        }

        $query->when($this->filters['race_racer_number'] ?? null,
            fn($q, $v) => $q->whereHas('registration', fn($registration) => $registration->where('racer_number', 'like', "%{$v}%")));

        $query->when($this->filters['race_vehicle'] ?? null,
            fn($q, $v) => $q->where('vehicle', 'like', "%{$v}%"));

        $query->when($this->filters['race_chassis_number'] ?? null,
            fn($q, $v) => $q->where('rangka_number', 'like', "%{$v}%"));

        $query->when($this->filters['race_engine_number'] ?? null,
            fn($q, $v) => $q->where('vehicle_number', 'like', "%{$v}%"));

        $query->when(
            ($this->filters['race_racer_name'] ?? null)
            || ($this->filters['race_nik'] ?? null)
            || ($this->filters['race_city'] ?? null)
            || (($this->filters['race_has_photo'] ?? '') !== '')
            || (($this->filters['race_has_kis'] ?? '') !== '')
            || (($this->filters['race_has_kta'] ?? '') !== ''),
            function ($q) {

                $q->whereHas('registration.racer', function ($racer) {

                    if (!empty($this->filters['race_racer_name'])) {
                        $racer->where('full_name', 'like', '%' . $this->filters['race_racer_name'] . '%');
                    }

                    if (!empty($this->filters['race_nik'])) {
                        $racer->where('nik', 'like', '%' . $this->filters['race_nik'] . '%');
                    }

                    if (!empty($this->filters['race_city'])) {
                        $racer->where('hometown', 'like', '%' . $this->filters['race_city'] . '%');
                    }

                    if (($this->filters['race_has_photo'] ?? '') === '1') {
                        $racer->whereNotNull('photo');
                    }

                    if (($this->filters['race_has_photo'] ?? '') === '0') {
                        $racer->whereNull('photo');
                    }

                    if (($this->filters['race_has_kis'] ?? '') === '1') {
                        $racer->whereNotNull('kis');
                    }

                    if (($this->filters['race_has_kis'] ?? '') === '0') {
                        $racer->whereNull('kis');
                    }

                    if (($this->filters['race_has_kta'] ?? '') === '1') {
                        $racer->whereNotNull('kta');
                    }

                    if (($this->filters['race_has_kta'] ?? '') === '0') {
                        $racer->whereNull('kta');
                    }
                });
            }
        );

        $query->when($this->filters['race_team_name'] ?? null, function ($q, $value) {

            $q->whereHas('registration', function ($registration) use ($value) {
                $registration->where('team_name', 'like', "%{$value}%");
            });

        });

        $query->when($this->filters['race_class_name'] ?? null,
            function ($q, $value) {

                $q->whereHas('eventClass', function ($class) use ($value) {
                    $class->where('name', 'like', "%{$value}%");
                });

            });

        $duplicateNumbers = DB::table('registration_classes')
            ->join('registrations', 'registration_classes.registration_id', '=', 'registrations.id')
            ->where('registration_classes.event_id', $eventId)
            ->where('registrations.race_status', 'approved')
            ->whereNotNull('registration_classes.racer_number')
            ->groupBy('registration_classes.racer_number')
            ->havingRaw('COUNT(DISTINCT registrations.racer_id) > 1')
            ->pluck('registration_classes.racer_number')
            ->toArray();

        if (($this->filters['race_racer_number_duplicate'] ?? '') === 'duplicate') {
            $query->whereIn('racer_number', $duplicateNumbers);
        }

        if (($this->filters['race_racer_number_duplicate'] ?? '') === 'unique') {
            $query->whereNotIn('racer_number', $duplicateNumbers);
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
