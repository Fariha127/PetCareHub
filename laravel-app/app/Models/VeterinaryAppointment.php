<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeterinaryAppointment extends Model
{
    protected $primaryKey = 'appointment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            if (empty($model->appointment_id)) {
                $model->appointment_id = strtoupper(str_replace('-', '', \Illuminate\Support\Str::uuid()->toString()));
            }
        });
    }

    protected $fillable = [
        'pet_id',
        'vet_id',
        'requested_by',
        'appointment_date',
        'reason',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id', 'user_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }
}
