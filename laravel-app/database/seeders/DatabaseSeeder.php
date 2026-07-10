<?php

namespace Database\Seeders;

use App\Models\AdoptionRequest;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $staff = User::updateOrCreate(['email' => 'staff@petcarehub.test'], [
            'full_name' => 'Shelter Staff',
            'password' => Hash::make('password'),
            'phone' => '01710000001',
            'role' => 'SHELTER_STAFF',
            'address' => 'Greenfield Shelter Office',
        ]);

        $vet = User::updateOrCreate(['email' => 'vet@petcarehub.test'], [
            'full_name' => 'Dr. Nadia Rahman',
            'password' => Hash::make('password'),
            'phone' => '01710000002',
            'role' => 'VETERINARIAN',
            'address' => 'PetCareHub Clinic',
        ]);

        $adopter = User::updateOrCreate(['email' => 'user@petcarehub.test'], [
            'full_name' => 'Aminul Karim',
            'password' => Hash::make('password'),
            'phone' => '01710000003',
            'role' => 'USER',
            'address' => 'Dhaka',
        ]);

        $luna = Pet::updateOrCreate(['pet_name' => 'Luna'], [
            'species' => 'Dog',
            'breed' => 'Labrador Mix',
            'age' => 3,
            'gender' => 'FEMALE',
            'vaccination_status' => 'VACCINATED',
            'health_condition' => 'Healthy and active',
            'adoption_status' => 'PENDING',
            'image_path' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=900&q=80',
        ]);

        $milo = Pet::updateOrCreate(['pet_name' => 'Milo'], [
            'species' => 'Cat',
            'breed' => 'Tabby',
            'age' => 2,
            'gender' => 'MALE',
            'vaccination_status' => 'PARTIAL',
            'health_condition' => 'Needs next vaccine next month',
            'adoption_status' => 'AVAILABLE',
            'image_path' => 'https://images.unsplash.com/photo-1519052537078-e6302a4968d4?auto=format&fit=crop&w=900&q=80',
        ]);

        Pet::updateOrCreate(['pet_name' => 'Coco'], [
            'species' => 'Rabbit',
            'breed' => 'Mini Lop',
            'age' => 1,
            'gender' => 'UNKNOWN',
            'vaccination_status' => 'NOT_VACCINATED',
            'health_condition' => 'Under observation',
            'adoption_status' => 'AVAILABLE',
            'image_path' => 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?auto=format&fit=crop&w=900&q=80',
        ]);

        AdoptionRequest::firstOrCreate([
            'user_id' => $adopter->user_id,
            'pet_id' => $luna->pet_id,
        ], [
            'request_date' => now()->subDays(2),
            'status' => 'PENDING',
        ]);

        MedicalRecord::firstOrCreate([
            'pet_id' => $milo->pet_id,
            'vet_id' => $vet->user_id,
            'diagnosis' => 'Routine checkup',
        ], [
            'diagnosis' => 'Routine checkup',
            'treatment' => 'General wellness care',
            'vaccination_date' => now()->subDays(15),
            'next_vaccine_date' => now()->addMonth(),
            'prescription' => 'Vitamin supplement',
        ]);
    }
}
