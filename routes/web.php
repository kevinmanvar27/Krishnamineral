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
use App\Http\Controllers\PurchaseMaterialsController;
use App\Http\Controllers\PurchaseDriverController;
use App\Http\Controllers\PurchaseLoadingController;
use App\Http\Controllers\PurchaseVehicleController;
use App\Http\Controllers\PurchaseQuarryController;
use App\Http\Controllers\PurchaseReceiverController;
use App\Http\Controllers\PurchaseQuarryReceiverController;
use App\Http\Controllers\PurchaseController;

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
    Route::get('/purchaseMaterials/editindex', [PurchaseMaterialsController::class, 'editIndex'])->name('purchaseMaterials.editIndex');
    Route::get('/purchaseDriver/editindex', [PurchaseDriverController::class, 'editIndex'])->name('purchaseDriver.editIndex');
    Route::get('/purchaseLoading/editindex', [PurchaseLoadingController::class, 'editIndex'])->name('purchaseLoading.editIndex');
    Route::get('/purchaseVehicle/editindex', [PurchaseVehicleController::class, 'editIndex'])->name('purchaseVehicle.editIndex');
    Route::get('/purchaseQuarry/editindex', [PurchaseQuarryController::class, 'editIndex'])->name('purchaseQuarry.editIndex');
    Route::get('/purchaseReceiver/editindex', [PurchaseReceiverController::class, 'editIndex'])->name('purchaseReceiver.editIndex');
    Route::get('/purchase/editindex', [PurchaseController::class, 'editIndex'])->name('purchase.editIndex');

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
    Route::resource('purchaseMaterials', PurchaseMaterialsController::class);
    Route::resource('purchaseDriver', PurchaseDriverController::class);
    Route::resource('purchaseLoading', PurchaseLoadingController::class);
    Route::resource('purchaseVehicle', PurchaseVehicleController::class);
    Route::resource('purchaseQuarry', PurchaseQuarryController::class);
    Route::resource('purchaseReceiver', PurchaseReceiverController::class);
    Route::resource('purchase', PurchaseController::class);


    Route::post('/vehicle/fetch-details', [VehicleController::class, 'fetchDetails'])->name('vehicle.details');

    Route::get('/settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

    

    
    Route::post('/change-password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('user.changePassword');
    
    Route::get('/users/{user}/credentials-pdf', [UserController::class, 'streamPdf'])->name('users.credentials-pdf');
    Route::get('/sales/{id}/sales-pdf', [SalesController::class, 'salesPdf'])->name('sales.sales-pdf');
    Route::get('/purchase/{id}/purchase-pdf', [PurchaseController::class, 'purchasePdf'])->name('purchase.purchase-pdf');

});

