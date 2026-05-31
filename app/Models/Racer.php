<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Racer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'racers';

    protected $fillable = [
        'user_id',
        'nik',
        'full_name',
        'short_name',
        'phone_number',
        'birth_location',
        'birth_date',
        'hometown',
        'photo',
        'kta',
        'kis',
        'racer_number',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'nik' => 'string',
        'full_name' => 'string',
        'short_name' => 'string',
        'phone_number' => 'string',
        'birth_location' => 'string',
        'birth_date' => 'date',
        'hometown' => 'string',
        'photo' => 'string',
        'kta' => 'string',
        'kis' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
