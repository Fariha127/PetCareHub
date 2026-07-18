<?php

use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\EventController;
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
    Route::post('/dashboard/user/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.user.profile.update');
    Route::get('/dashboard/user/requests', [DashboardController::class, 'userRequests'])->name('dashboard.user.requests');
    Route::get('/dashboard/user/appointments', [DashboardController::class, 'userAppointments'])->name('dashboard.user.appointments');
    Route::get('/dashboard/user/pets', [DashboardController::class, 'userPets'])->name('dashboard.user.pets');
    Route::get('/veterinary/appointments', [VeterinaryController::class, 'appointments'])->name('veterinary.appointments');
    Route::post('/veterinary/appointments', [VeterinaryController::class, 'storeAppointment'])->name('veterinary.appointments.store');
    Route::post('/events/{event}/enroll', [EventController::class, 'enroll'])->name('events.enroll');
});

Route::middleware(['auth', 'role:SHELTER_STAFF,ADMIN'])->group(function () {
    Route::get('/dashboard/shelter', [DashboardController::class, 'shelterDashboard'])->name('dashboard.shelter');
    Route::get('/dashboard/shelter/add-pet', [DashboardController::class, 'shelterAddPet'])->name('dashboard.shelter.add_pet');
    Route::get('/dashboard/shelter/requests', [DashboardController::class, 'shelterRequests'])->name('dashboard.shelter.requests');
    Route::get('/dashboard/shelter/events', [DashboardController::class, 'shelterEvents'])->name('dashboard.shelter.events');
    Route::get('/dashboard/shelter/pets', [DashboardController::class, 'shelterPets'])->name('dashboard.shelter.pets');
    Route::post('/adoptions/{adoptionRequest}/approve', [AdoptionController::class, 'approve'])->name('adoptions.approve');
    Route::post('/adoptions/{adoptionRequest}/reject', [AdoptionController::class, 'reject'])->name('adoptions.reject');
    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::put('/pets/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

Route::middleware(['auth', 'role:VETERINARIAN,ADMIN'])->group(function () {
    Route::get('/dashboard/vet', [DashboardController::class, 'vetDashboard'])->name('dashboard.vet');
    Route::get('/dashboard/vet/add-record', [DashboardController::class, 'vetAddRecord'])->name('dashboard.vet.add_record');
    Route::get('/dashboard/vet/appointments', [DashboardController::class, 'vetAppointments'])->name('dashboard.vet.appointments');
    Route::post('/dashboard/vet/appointments/{appointment}/status', [DashboardController::class, 'updateAppointmentStatus'])->name('dashboard.vet.appointments.status');
    Route::post('/veterinary/records', [VeterinaryController::class, 'storeRecord'])->name('veterinary.records.store');
});

Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'shelterDashboard'])->name('dashboard.admin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/password', [DashboardController::class, 'changePasswordView'])->name('dashboard.password');
    Route::post('/dashboard/password', [DashboardController::class, 'updatePassword'])->name('dashboard.password.update');
});
