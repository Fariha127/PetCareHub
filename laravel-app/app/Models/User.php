<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            if (empty($model->user_id)) {
                $model->user_id = strtoupper(str_replace('-', '', \Illuminate\Support\Str::uuid()->toString()));
            }
        });
    }

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone',
        'role',
        'address',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class, 'user_id', 'user_id');
    }

    public function reviewedRequests()
    {
        return $this->hasMany(AdoptionRequest::class, 'reviewed_by', 'user_id');
    }

    public function veterinaryAppointments()
    {
        return $this->hasMany(VeterinaryAppointment::class, 'requested_by', 'user_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'vet_id', 'user_id');
    }

}
