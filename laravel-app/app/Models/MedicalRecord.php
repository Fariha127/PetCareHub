<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $primaryKey = 'record_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            if (empty($model->record_id)) {
                $model->record_id = strtoupper(str_replace('-', '', \Illuminate\Support\Str::uuid()->toString()));
            }
        });
    }

    protected $fillable = [
        'pet_id',
        'vet_id',
        'diagnosis',
        'treatment',
        'vaccination_date',
        'next_vaccine_date',
        'prescription',
    ];

    protected $casts = [
        'vaccination_date' => 'date',
        'next_vaccine_date' => 'date',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id', 'user_id');
    }
}
