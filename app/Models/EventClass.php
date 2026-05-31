<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event_classes';

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'price_fine',
        'cc',
        'quota',
        'notes',
    ];

    protected $casts = [
        'price' => 'integer',
        'price_fine' => 'integer',
        'cc' => 'integer',
        'quota' => 'integer',
        'is_active' => 'boolean',
    ];
}
