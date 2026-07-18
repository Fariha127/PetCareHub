<?php

namespace Database\Seeders;

use App\Models\AdoptionRequest;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
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
            'food_preference' => 'Premium dry kibble, chicken treats',
            'distinct_habit' => 'Loves catching frisbees and playing fetch',
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
            'food_preference' => 'Wet canned food (tuna), fresh water',
            'distinct_habit' => 'Loves sleeping in empty cardboard boxes',
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
            'food_preference' => 'Fresh alfalfa hay, carrots, leafy greens',
            'distinct_habit' => 'Hops around excitedly when someone approaches',
        ]);

        $bella = Pet::updateOrCreate(['pet_name' => 'Bella'], [
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'age' => 2,
            'gender' => 'FEMALE',
            'vaccination_status' => 'FULLY_VACCINATED',
            'health_condition' => 'Very healthy and social',
            'adoption_status' => 'AVAILABLE',
            'image_path' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=900&q=80',
            'food_preference' => 'Chicken and rice dry kibble',
            'distinct_habit' => 'Loves swimming and fetching tennis balls',
        ]);

        $casper = Pet::updateOrCreate(['pet_name' => 'Casper'], [
            'species' => 'Cat',
            'breed' => 'Persian',
            'age' => 1,
            'gender' => 'MALE',
            'vaccination_status' => 'FULLY_VACCINATED',
            'health_condition' => 'Healthy and playful',
            'adoption_status' => 'AVAILABLE',
            'image_path' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=900&q=80',
            'food_preference' => 'Salmon wet food, kitten dry crunchies',
            'distinct_habit' => 'Purrs very loudly when brushed or scratched',
        ]);

        $max = Pet::updateOrCreate(['pet_name' => 'Max'], [
            'species' => 'Dog',
            'breed' => 'German Shepherd',
            'age' => 4,
            'gender' => 'MALE',
            'vaccination_status' => 'PARTIALLY_VACCINATED',
            'health_condition' => 'Energetic and alert',
            'adoption_status' => 'AVAILABLE',
            'image_path' => 'https://images.unsplash.com/photo-1589941013453-ec89f33b5e95?auto=format&fit=crop&w=900&q=80',
            'food_preference' => 'Raw beef diet and large bone biscuits',
            'distinct_habit' => 'Very protective and highly trainable, sits on command',
        ]);

        $rocky = Pet::updateOrCreate(['pet_name' => 'Rocky'], [
            'species' => 'Cat',
            'breed' => 'British Shorthair',
            'age' => 3,
            'gender' => 'MALE',
            'vaccination_status' => 'NOT_VACCINATED',
            'health_condition' => 'Calm and quiet',
            'adoption_status' => 'AVAILABLE',
            'image_path' => 'https://plus.unsplash.com/premium_photo-1677545183884-421157b2da02?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'food_preference' => 'Grain-free dry food, salmon treats',
            'distinct_habit' => 'Loves to sit by the window and watch birds all day',
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

        // Seed Events in Bangladesh
        \App\Models\Event::updateOrCreate(['title' => 'Dhanmondi Lake Stray Feeding Campaign'], [
            'description' => 'Help us distribute healthy cooked food, kibbles, and clean water to stray dogs and cats around Dhanmondi Lake. Volunteers are encouraged to join and bring dry foods.',
            'event_date' => now()->addDays(5),
            'location' => 'Rabindra Sarobar Area, Dhanmondi Lake, Dhaka, Bangladesh',
            'created_by' => $staff->user_id,
        ]);

        \App\Models\Event::updateOrCreate(['title' => 'Chittagong Free Rabies Vaccination Camp'], [
            'description' => 'A community welfare event offering free Rabies vaccinations and quick medical checks for stray dogs and outdoor pets. Organized by PetCareHub vet clinic.',
            'event_date' => now()->addDays(10),
            'location' => 'GEC Circle Open Ground, Chittagong, Bangladesh',
            'created_by' => $staff->user_id,
        ]);

        \App\Models\Event::updateOrCreate(['title' => 'Sylhet Rescue Pet Adoption Meetup'], [
            'description' => 'Come and interact with our loving rescue animals up for adoption. Get free consultations from our veterinarian on pet health and behavior.',
            'event_date' => now()->addDays(12),
            'location' => 'Shahi Eidgah Open Field, Sylhet, Bangladesh',
            'created_by' => $staff->user_id,
        ]);
    }
}
