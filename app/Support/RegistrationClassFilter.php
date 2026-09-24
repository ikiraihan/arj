<?php

namespace App\Support;

use App\Models\Registration;
use App\Models\RegistrationClass;
use Illuminate\Database\Eloquent\Builder;

class RegistrationClassFilter
{
    public static function apply(array $filters): Builder
    {
        $query = RegistrationClass::query()
            ->whereHas('registration', function ($q) {
                $q->withoutTrashed()->where('race_status', 'approved');
            })
            ->with(['registration.racer.user', 'registration', 'event', 'eventClass'])
            ->orderBy(
                Registration::select('race_status_approved_at')
                    ->whereColumn('registrations.id', 'registration_classes.registration_id')
                    ->limit(1)
            );

        if (!empty($filters['event_id'])) {
            $query->where('event_id', $filters['event_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('registration', fn ($qq) => $qq
                        ->where('registration_number', 'like', "%{$search}%")
                        ->orWhere('team_name', 'like', "%{$search}%"))
                    ->orWhereHas('registration.racer', fn ($qq) => $qq
                        ->where('full_name', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['race_racer_ids'])) {
            $ids = (array) $filters['race_racer_ids'];
            $query->whereHas('registration', fn ($q) => $q->whereIn('racer_id', $ids));
        }

        if (!empty($filters['race_team_names'])) {
            $teams = (array) $filters['race_team_names'];
            $query->whereHas('registration', fn ($q) => $q->whereIn('team_name', $teams));
        }

        if (!empty($filters['race_receipt_number'])) {
            $query->whereHas('registration', fn ($q) => $q
                ->where('registration_number', 'like', '%' . $filters['race_receipt_number'] . '%'));
        }

        if (!empty($filters['race_nik'])) {
            $query->whereHas('registration.racer', fn ($q) => $q
                ->where('nik', 'like', '%' . $filters['race_nik'] . '%'));
        }

        if (!empty($filters['race_racer_number'])) {
            $query->whereHas('registration', fn ($q) => $q
                ->where('racer_number', 'like', '%' . $filters['race_racer_number'] . '%'));
        }

        if (!empty($filters['race_city'])) {
            $query->whereHas('registration.racer', fn ($q) => $q
                ->where('hometown', 'like', '%' . $filters['race_city'] . '%'));
        }

        if (!empty($filters['race_class_ids'])) {
            $query->whereIn('event_class_id', (array) $filters['race_class_ids']);
        }

        if (!empty($filters['race_vehicle'])) {
            $query->where('vehicle', 'like', '%' . $filters['race_vehicle'] . '%');
        }

        if (!empty($filters['race_chassis_number'])) {
            $query->where('rangka_number', 'like', '%' . $filters['race_chassis_number'] . '%');
        }

        if (!empty($filters['race_engine_number'])) {
            $query->where('vehicle_number', 'like', '%' . $filters['race_engine_number'] . '%');
        }

        foreach (['race_has_photo' => 'photo', 'race_has_kis' => 'kis', 'race_has_kta' => 'kta'] as $key => $column) {
            if (isset($filters[$key]) && $filters[$key] !== '') {
                $has = filter_var($filters[$key], FILTER_VALIDATE_BOOLEAN);
                $query->whereHas('registration.racer', fn ($q) =>
                    $has ? $q->whereNotNull($column) : $q->whereNull($column));
            }
        }

        // if (!empty($this->filters['event_class_id'])) {
        //     $query->where('event_class_id', $this->filters['event_class_id']);
        // }

        // if (!empty($this->filters['search'])) {

        //     $search = $this->filters['search'];

        //     $query->where(function ($q) use ($search) {

        //         $q->where('invoice_number', 'like', "%{$search}%")
        //             ->orWhere('racer_number', 'like', "%{$search}%")
        //             ->orWhere('vehicle', 'like', "%{$search}%")
        //             ->orWhereHas('registration', function ($registration) use ($search) {
        //                 $registration->where('team_name', 'like', "%{$search}%");
        //             })
        //             ->orWhereHas('registration.racer', function ($racer) use ($search) {
        //                 $racer->where('full_name', 'like', "%{$search}%")
        //                     ->orWhere('nik', 'like', "%{$search}%");
        //             });

        //     });
        // }

        // $query->when($this->filters['race_racer_number'] ?? null,
        //     fn($q, $v) => $q->whereHas('registration', fn($registration) => $registration->where('racer_number', 'like', "%{$v}%")));

        // $query->when($this->filters['race_vehicle'] ?? null,
        //     fn($q, $v) => $q->where('vehicle', 'like', "%{$v}%"));

        // $query->when($this->filters['race_chassis_number'] ?? null,
        //     fn($q, $v) => $q->where('rangka_number', 'like', "%{$v}%"));

        // $query->when($this->filters['race_engine_number'] ?? null,
        //     fn($q, $v) => $q->where('vehicle_number', 'like', "%{$v}%"));

        // $query->when(
        //     ($this->filters['race_racer_name'] ?? null)
        //     || ($this->filters['race_nik'] ?? null)
        //     || ($this->filters['race_city'] ?? null)
        //     || (($this->filters['race_has_photo'] ?? '') !== '')
        //     || (($this->filters['race_has_kis'] ?? '') !== '')
        //     || (($this->filters['race_has_kta'] ?? '') !== ''),
        //     function ($q) {

        //         $q->whereHas('registration.racer', function ($racer) {

        //             if (!empty($this->filters['race_racer_name'])) {
        //                 $racer->where('full_name', 'like', '%' . $this->filters['race_racer_name'] . '%');
        //             }

        //             if (!empty($this->filters['race_nik'])) {
        //                 $racer->where('nik', 'like', '%' . $this->filters['race_nik'] . '%');
        //             }

        //             if (!empty($this->filters['race_city'])) {
        //                 $racer->where('hometown', 'like', '%' . $this->filters['race_city'] . '%');
        //             }

        //             if (($this->filters['race_has_photo'] ?? '') === '1') {
        //                 $racer->whereNotNull('photo');
        //             }

        //             if (($this->filters['race_has_photo'] ?? '') === '0') {
        //                 $racer->whereNull('photo');
        //             }

        //             if (($this->filters['race_has_kis'] ?? '') === '1') {
        //                 $racer->whereNotNull('kis');
        //             }

        //             if (($this->filters['race_has_kis'] ?? '') === '0') {
        //                 $racer->whereNull('kis');
        //             }

        //             if (($this->filters['race_has_kta'] ?? '') === '1') {
        //                 $racer->whereNotNull('kta');
        //             }

        //             if (($this->filters['race_has_kta'] ?? '') === '0') {
        //                 $racer->whereNull('kta');
        //             }
        //         });
        //     }
        // );

        // $query->when($this->filters['race_team_name'] ?? null, function ($q, $value) {

        //     $q->whereHas('registration', function ($registration) use ($value) {
        //         $registration->where('team_name', 'like', "%{$value}%");
        //     });

        // });

        // $query->when($this->filters['race_class_name'] ?? null,
        //     function ($q, $value) {

        //         $q->whereHas('eventClass', function ($class) use ($value) {
        //             $class->where('name', 'like', "%{$value}%");
        //         });

        //     });

        // $duplicateNumbers = DB::table('registration_classes')
        //     ->join('registrations', 'registration_classes.registration_id', '=', 'registrations.id')
        //     ->where('registration_classes.event_id', $eventId)
        //     ->where('registrations.race_status', 'approved')
        //     ->whereNotNull('registration_classes.racer_number')
        //     ->groupBy('registration_classes.racer_number')
        //     ->havingRaw('COUNT(DISTINCT registrations.racer_id) > 1')
        //     ->pluck('registration_classes.racer_number')
        //     ->toArray();

        // if (($this->filters['race_racer_number_duplicate'] ?? '') === 'duplicate') {
        //     $query->whereIn('racer_number', $duplicateNumbers);
        // }

        // if (($this->filters['race_racer_number_duplicate'] ?? '') === 'unique') {
        //     $query->whereNotIn('racer_number', $duplicateNumbers);
        // }

        return $query;
    }
}
