<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $primaryKey = 'pet_id';

    private const FALLBACK_IMAGES = [
        'CAT' => 'https://images.unsplash.com/photo-1519052537078-e6302a4968d4?auto=format&fit=crop&w=1200&q=80',
        'DOG' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=1200&q=80',
        'RABBIT' => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=1200&q=80',
    ];

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

    public function getPhotoUrlAttribute(): string
    {
        if (filled($this->image_path)) {
            return $this->image_path;
        }

        return self::FALLBACK_IMAGES[strtoupper((string) $this->species)]
            ?? self::FALLBACK_IMAGES['DOG'];
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
