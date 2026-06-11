<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $primaryKey = 'pet_id';

    protected $fillable = [
        'pet_name',
        'species',
        'breed',
        'age',
        'gender',
        'vaccination_status',
        'health_condition',
        'adoption_status',
        'image_path',
    ];

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class, 'pet_id', 'pet_id');
    }

    public function veterinaryAppointments()
    {
        return $this->hasMany(VeterinaryAppointment::class, 'pet_id', 'pet_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'pet_id', 'pet_id');
    }
}
