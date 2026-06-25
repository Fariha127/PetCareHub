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

        return view('pets.show', compact('pet'));
    }

    public function store(Request $request)
    {
        Pet::create($request->validate([
            'pet_name' => ['required', 'string', 'max:100'],
            'species' => ['required', 'string', 'max:60'],
            'breed' => ['nullable', 'string', 'max:80'],
            'age' => ['required', 'integer', 'min:0'],
            'gender' => ['required', 'string'],
            'vaccination_status' => ['required', 'string'],
            'health_condition' => ['nullable', 'string'],
            'adoption_status' => ['required', 'string'],
            'image_path' => ['nullable', 'string'],
        ]));

        return back()->with('success', 'Pet saved successfully.');
    }

    public function update(Request $request, Pet $pet)
    {
        $pet->update($request->validate([
            'pet_name' => ['required', 'string', 'max:100'],
            'species' => ['required', 'string', 'max:60'],
            'breed' => ['nullable', 'string', 'max:80'],
            'age' => ['required', 'integer', 'min:0'],
            'gender' => ['required', 'string'],
            'vaccination_status' => ['required', 'string'],
            'health_condition' => ['nullable', 'string'],
            'adoption_status' => ['required', 'string'],
            'image_path' => ['nullable', 'string'],
        ]));

        return back()->with('success', 'Pet updated successfully.');
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();

        return back()->with('success', 'Pet deleted successfully.');
    }
}
