<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'registration_classes';

    protected $fillable = [
        'registration_id',
        'event_id',
        'class_id',
        'invoice_number',
        'racer_number',
        'vehicle',
        'vehicle_number',
        'rangka_number',
    ];

    // protected $casts = [
    //     'registration_id' => 'integer',
    //     'event_id' => 'integer',
    //     'class_id' => 'integer',
    //     'invoice_number' => 'string',
    //     'racer_number' => 'string',
    //     'vehicle' => 'string',
    //     'vehicle_number' => 'string',
    //     'rangka_number' => 'string',
    // ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function eventClass()
    {
        return $this->belongsTo(EventClass::class, 'class_id');
    }
}
