<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'registrations';

    protected $fillable = [
        'event_id',
        'racer_id',
        'user_id',
        'registration_number',
        'team_name',
        'phone_number',
        'status',
        'race_status',
        'notes',
        'payment_proof',
        'payment_method',
        'is_fined',
        'racer_number',
        'name_register',
        'phone_number_register',
    ];

    protected $casts = [
        'event_id' => 'integer',
        'racer_id' => 'integer',
        'user_id' => 'integer',
        'registration_number' => 'string',
        'phone_number' => 'string',
        'status' => 'string',
        'race_status' => 'string',
        'notes' => 'string',
        'payment_proof' => 'string',
        'payment_method' => 'string',
    ];

    public function registrationClasses()
    {
        return $this->hasMany(RegistrationClass::class);
    }


    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function racer()
    {
        return $this->belongsTo(Racer::class, 'racer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
