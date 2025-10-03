<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\VehicleController;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\PlacesController;
use App\Http\Controllers\RoyaltyController;
use App\Http\Controllers\LoadingController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PartyController;

// Redirect root URL to /home if logged in, or to login otherwise
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login'); // or return view('welcome');
});

// Auth routes (login, register, forgot password, etc.)
Auth::routes();

// Home page after login
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('/register', [SalesController::class, 'register'])->name('users.register');

// Protected routes (only accessible when logged in)
Route::middleware(['auth'])->group(function () {

    Route::get('/users/editindex', [UserController::class, 'editIndex'])->name('users.editIndex');
    Route::get('/sales/editindex', [SalesController::class, 'editIndex'])->name('sales.editIndex');
    Route::get('/materials/editindex', [MaterialsController::class, 'editIndex'])->name('materials.editIndex');
    Route::get('/places/editindex', [PlacesController::class, 'editIndex'])->name('places.editIndex');
    Route::get('/vehicles/editindex', [VehicleController::class, 'editIndex'])->name('vehicles.editIndex');
    Route::get('/royalty/editindex', [RoyaltyController::class, 'editIndex'])->name('royalty.editIndex');
    Route::get('/loading/editindex', [LoadingController::class, 'editIndex'])->name('loading.editIndex');
    Route::get('/driver/editindex', [DriverController::class, 'editIndex'])->name('driver.editIndex');
    Route::get('/party/editindex', [PartyController::class, 'editIndex'])->name('party.editIndex');

    Route::get('/sales/pendingLoads', [SalesController::class, 'pendingLoad'])->name('sales.pendingLoad');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('vehicle', VehicleController::class);
    Route::resource('materials', MaterialsController::class);
    Route::resource('places', PlacesController::class);
    Route::resource('royalty', RoyaltyController::class);
    Route::resource('loading', LoadingController::class);
    Route::resource('driver', DriverController::class);
    Route::resource('party', PartyController::class);

    Route::post('/vehicle/fetch-details', [VehicleController::class, 'fetchDetails'])->name('vehicle.details');

    Route::get('/settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

    

    
    Route::post('/change-password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('user.changePassword');
    
    Route::get('/users/{user}/credentials-pdf', [UserController::class, 'streamPdf'])->name('users.credentials-pdf');
    Route::get('/sales/{id}/sales-pdf', [SalesController::class, 'salesPdf'])->name('sales.sales-pdf');

});

