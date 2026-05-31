<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistrationOriginal extends Model
{
    use SoftDeletes;

    /**
     * Nama tabel yang terkait dengan model.
     * Karena tidak menggunakan jamak standar Laravel (registrations_originals),
     * kita wajib mendefinisikannya secara eksplisit.
     *
     * @var string
     */
    protected $table = 'registrations_originals';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'event_id',
        'class_id',
        'racer_id',
        'user_id',
        'registration_number',
        'team_name',
        'notes',
        'invoice_number',
        'vehicle',
        'vehicle_number',
        'rangka_number',
        'name_register',
        'phone_number_register',
        'racer_number',
        'payment_method',
        'is_fined',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu (casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_fined' => 'boolean',
        'registration_number' => 'integer',
        'team_name' => 'string',
        'invoice_number' => 'integer',
        'vehicle_number' => 'integer',
        'rangka_number' => 'integer',
        'racer_number' => 'integer',
    ];

    /**
     * Relasi ke model Registration
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Relasi ke model Event
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Relasi ke model Racer
     */
    public function racer(): BelongsTo
    {
        return $this->belongsTo(Racer::class, 'racer_id');
    }

    /**
     * Relasi ke model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function eventClass(): BelongsTo
    {
        return $this->belongsTo(EventClass::class, 'class_id');
    }
}
