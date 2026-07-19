<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $pets = Pet::query();

        if ($request->filled('keyword')) {
            $keyword = $request->string('keyword')->toString();
            $pets->where(function ($query) use ($keyword) {
                $query->where('pet_name', 'like', '%' . $keyword . '%')
                    ->orWhere('breed', 'like', '%' . $keyword . '%')
                    ->orWhere('species', 'like', '%' . $keyword . '%');
            });
        }

        foreach (['species', 'breed', 'age', 'vaccination_status', 'adoption_status'] as $field) {
            if ($request->filled($field)) {
                $pets->where($field, $request->input($field));
            }
        }

        if ($request->input('sort') === 'age') {
            $pets->orderBy('age');
        } else {
            $pets->latest();
        }

        return view('pets.index', [
            'pets' => $pets->paginate(12)->withQueryString(),
            'speciesOptions' => Pet::query()
                ->select('species')
                ->whereNotNull('species')
                ->distinct()
                ->orderBy('species')
                ->pluck('species'),
            'breedOptions' => Pet::query()
                ->select('breed')
                ->whereNotNull('breed')
                ->where('breed', '!=', '')
                ->distinct()
                ->orderBy('breed')
                ->pluck('breed'),
        ]);
    }

    public function show(Pet $pet)
    {
        $pet->load(['adoptionRequests.user', 'medicalRecords.vet']);

        $ageGroupResult = \Illuminate\Support\Facades\DB::select("SELECT fn_get_pet_age_group(:age) AS age_group FROM DUAL", ['age' => $pet->age]);
        $ageGroup = $ageGroupResult[0]->age_group ?? 'Unknown';

        return view('pets.show', compact('pet', 'ageGroup'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pet_name' => ['required', 'string', 'max:100'],
            'species' => ['required', 'string', 'max:60'],
            'breed' => ['nullable', 'string', 'max:80'],
            'age' => ['required', 'numeric', 'min:0'],
            'gender' => ['required', 'string'],
            'vaccination_status' => ['required', 'string'],
            'health_condition' => ['nullable', 'string'],
            'adoption_status' => ['nullable', 'string'],
            'image_path' => ['nullable', 'string'],
            'food_preference' => ['nullable', 'string', 'max:255'],
            'distinct_habit' => ['nullable', 'string', 'max:255'],
        ]);

        if (empty($data['adoption_status'])) {
            $data['adoption_status'] = 'AVAILABLE';
        }

        \Illuminate\Support\Facades\DB::statement(
            "BEGIN sp_create_pet(:pet_name, :species, :breed, :age, :gender, :vaccination_status, :health_condition, :adoption_status, :image_path, :food_preference, :distinct_habit); END;",
            [
                'pet_name' => $data['pet_name'],
                'species' => $data['species'],
                'breed' => $data['breed'] ?? null,
                'age' => $data['age'],
                'gender' => $data['gender'],
                'vaccination_status' => $data['vaccination_status'],
                'health_condition' => $data['health_condition'] ?? null,
                'adoption_status' => $data['adoption_status'],
                'image_path' => $data['image_path'] ?? null,
                'food_preference' => $data['food_preference'] ?? null,
                'distinct_habit' => $data['distinct_habit'] ?? null,
            ]
        );

        return back()->with('success', 'Pet saved successfully.');
    }

    public function update(Request $request, Pet $pet)
    {
        $data = $request->validate([
            'pet_name' => ['required', 'string', 'max:100'],
            'species' => ['required', 'string', 'max:60'],
            'breed' => ['nullable', 'string', 'max:80'],
            'age' => ['required', 'numeric', 'min:0'],
            'gender' => ['required', 'string'],
            'vaccination_status' => ['required', 'string'],
            'health_condition' => ['nullable', 'string'],
            'adoption_status' => ['required', 'string'],
            'image_path' => ['nullable', 'string'],
            'food_preference' => ['nullable', 'string', 'max:255'],
            'distinct_habit' => ['nullable', 'string', 'max:255'],
        ]);

        \Illuminate\Support\Facades\DB::statement(
            "BEGIN sp_update_pet(:pet_id, :pet_name, :species, :breed, :age, :gender, :vaccination_status, :health_condition, :adoption_status, :image_path, :food_preference, :distinct_habit); END;",
            [
                'pet_id' => $pet->pet_id,
                'pet_name' => $data['pet_name'],
                'species' => $data['species'],
                'breed' => $data['breed'] ?? null,
                'age' => $data['age'],
                'gender' => $data['gender'],
                'vaccination_status' => $data['vaccination_status'],
                'health_condition' => $data['health_condition'] ?? null,
                'adoption_status' => $data['adoption_status'],
                'image_path' => $data['image_path'] ?? null,
                'food_preference' => $data['food_preference'] ?? null,
                'distinct_habit' => $data['distinct_habit'] ?? null,
            ]
        );

        return back()->with('success', 'Pet updated successfully.');
    }

    public function destroy(Pet $pet)
    {
        \Illuminate\Support\Facades\DB::statement(
            "BEGIN sp_delete_pet(:pet_id); END;",
            ['pet_id' => $pet->pet_id]
        );

        return back()->with('success', 'Pet deleted successfully.');
    }
}
