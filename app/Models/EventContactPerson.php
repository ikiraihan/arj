<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventContactPerson extends Model
{
    use HasFactory;

    protected $table = 'event_contact_persons';

    protected $fillable = [
        'event_id',
        'name',
        'phone_number',
    ];

    protected $casts = [
        'name' => 'string',
        'phone_number' => 'string',
    ];
}
