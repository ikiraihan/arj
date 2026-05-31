<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventPaymentAccount extends Model
{
    use HasFactory;

    protected $table = 'event_payment_accounts';

    protected $fillable = [
        'event_id',
        'bank_name',
        'account_number',
        'account_holder_name',
    ];

    protected $casts = [
        'event_id' => 'integer',
        'bank_name' => 'string',
        'account_number' => 'string',
        'account_holder_name' => 'string',
    ];
}
