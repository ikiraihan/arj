<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventPhoto extends Model
{
    use HasFactory;

    protected $table = 'event_photos';

    protected $fillable = [
        'event_id',
        'path',
        'position'
    ];

    protected $casts = [
        'event_id' => 'integer',
        'photo_path' => 'string',
        'position' => 'integer',
    ];
}
