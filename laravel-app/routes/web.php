<?php

use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\VeterinaryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/pets', [PetController::class, 'index'])->name('pets.index');
Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');

Route::middleware(['auth', 'role:USER'])->group(function () {
    Route::post('/adoptions', [AdoptionController::class, 'store'])->name('adoptions.store');
    Route::get('/dashboard/user', [DashboardController::class, 'userDashboard'])->name('dashboard.user');
    Route::get('/veterinary/appointments', [VeterinaryController::class, 'appointments'])->name('veterinary.appointments');
    Route::post('/veterinary/appointments', [VeterinaryController::class, 'storeAppointment'])->name('veterinary.appointments.store');
});

Route::middleware(['auth', 'role:SHELTER_STAFF,ADMIN'])->group(function () {
    Route::get('/dashboard/shelter', [DashboardController::class, 'shelterDashboard'])->name('dashboard.shelter');
    Route::post('/adoptions/{adoptionRequest}/approve', [AdoptionController::class, 'approve'])->name('adoptions.approve');
    Route::post('/adoptions/{adoptionRequest}/reject', [AdoptionController::class, 'reject'])->name('adoptions.reject');
    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::put('/pets/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');
});

Route::middleware(['auth', 'role:VETERINARIAN,ADMIN'])->group(function () {
    Route::get('/dashboard/vet', [DashboardController::class, 'vetDashboard'])->name('dashboard.vet');
    Route::post('/veterinary/records', [VeterinaryController::class, 'storeRecord'])->name('veterinary.records.store');
});

Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'shelterDashboard'])->name('dashboard.admin');
});
