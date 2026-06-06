<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'name',
        'description',
        'notes_transfer',
        'venue',
        'type',
        'provinsi',
        'kota',
        'link_maps',
        'registration_start_date',
        'registration_end_date',
        'start_date',
        'end_date',
        'is_active',
        'link_documentation',
        'link_documentation_active',
    ];

    protected $casts = [
        'registration_start_date' => 'datetime',
        'registration_end_date' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'link_documentation_active' => 'boolean',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function contacts()
    {
        return $this->hasMany(EventContactPerson::class);
    }

    public function paymentAccount()
    {
        return $this->hasOne(EventPaymentAccount::class);
    }

    public function classes()
    {
        return $this->hasMany(EventClass::class);
    }

    public function contactPerson()
    {
        return $this->hasOne(EventContactPerson::class);
    }

     public function contactPersons()
    {
        return $this->hasMany(EventContactPerson::class);
    }

    public function photos()
    {
        return $this->hasMany(EventPhoto::class);
    }

    public function photo()
    {
        return $this->hasOne(EventPhoto::class);
    }
}
