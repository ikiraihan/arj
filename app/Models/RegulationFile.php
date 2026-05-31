<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegulationFile extends Model
{
    use HasFactory;

    protected $table = 'regulation_files';

    protected $fillable = [
        'title',
        'description',
        'path',
        'is_active'
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'path' => 'string',
        'is_active' => 'boolean'
    ];
}
