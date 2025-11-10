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
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BlastingController;
use App\Http\Controllers\BlasterNameController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CartingController;
use App\Http\Controllers\DrillingController;
use App\Http\Controllers\DrillingNameController;
use App\Http\Controllers\VendorController;

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
    Route::get('/blasting/editindex', [BlastingController::class, 'editIndex'])->name('blasting.editIndex');
    Route::get('/blaster-name/editindex', [BlasterNameController::class, 'editIndex'])->name('blaster-name.editIndex');
    Route::get('/drilling/editindex', [DrillingController::class, 'editIndex'])->name('drilling.editIndex');
    Route::get('/drilling-name/editindex', [DrillingNameController::class, 'editIndex'])->name('drilling-name.editIndex');
    Route::get('/vendors/editindex', [VendorController::class, 'editIndex'])->name('vendors.editIndex');
    

    Route::get('/sales/pendingLoads', [SalesController::class, 'pendingLoad'])->name('sales.pendingLoad');
    Route::get('/purchase/pendingLoads', [PurchaseController::class, 'pendingLoad'])->name('purchase.pendingLoad');

    Route::get('/sales/audit', [SalesController::class, 'salesAudit'])->name('sales.salesAudit');
    Route::get('/sales/rate', [SalesController::class, 'rate'])->name('sales.rate');
    Route::get('/sales/statement', [SalesController::class, 'salesStatement'])->name('sales.statement');
    Route::get('/sales/statement/print', [SalesController::class, 'printStatement'])->name('sales.statement.print');
    Route::get('/sales/{id}/show-ajax', [SalesController::class, 'showAjax'])->name('sales.showAjax');
    Route::post('/sales/{id}/update-party-weight', [SalesController::class, 'updatePartyWeight'])->name('sales.updatePartyWeight');
    
    // New routes for rate and GST updates
    Route::post('/sales/{id}/update-rate', [SalesController::class, 'updateRate'])->name('sales.updateRate');
    Route::post('/sales/bulk-update-rate', [SalesController::class, 'bulkUpdateRate'])->name('sales.bulkUpdateRate');

    // Carting routes
    Route::get('/carting', [CartingController::class, 'index'])->name('carting.index');
    Route::post('/carting/{id}/update-carting', [CartingController::class, 'updateCarting'])->name('carting.updateCarting');
    Route::post('/carting/bulk-update-carting', [CartingController::class, 'bulkUpdateCarting'])->name('carting.bulkUpdateCarting');

    // Search route
    Route::post('/search-challans', [SalesController::class, 'searchChallans'])->name('search.challans');

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
    Route::resource('blasting', BlastingController::class);
    Route::resource('blaster-name', BlasterNameController::class);
    Route::resource('drilling', DrillingController::class);
    Route::resource('drilling-name', DrillingNameController::class);
    Route::resource('vendors', VendorController::class);


    Route::post('/vehicle/fetch-details', [VehicleController::class, 'fetchDetails'])->name('vehicle.details');
    Route::post('/purchaseVehicle/fetch-details', [PurchaseVehicleController::class, 'fetchDetails'])->name('purchaseVehicle.details');

    Route::get('/settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

    

    
    Route::post('/change-password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('user.changePassword');
    
    Route::get('/users/{user}/credentials-pdf', [UserController::class, 'streamPdf'])->name('users.credentials-pdf');
    Route::get('/sales/{id}/sales-pdf', [SalesController::class, 'salesPdf'])->name('sales.sales-pdf');
    Route::get('/purchase/{id}/purchase-pdf', [PurchaseController::class, 'purchasePdf'])->name('purchase.purchase-pdf');
    Route::get('/drilling/{id}/drilling-pdf', [DrillingController::class, 'drillingPdf'])->name('drilling.drilling-pdf');
    Route::get('/blasting/{id}/blasting-pdf', [BlastingController::class, 'blastingPdf'])->name('blasting.blasting-pdf');

    // Attendance routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/calendar', [AttendanceController::class, 'calendar'])->name('attendance.calendar');
    Route::get('/attendance/print', [AttendanceController::class, 'print'])->name('attendance.print');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    
    // Activity Log routes
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{id}', [ActivityLogController::class, 'show'])->name('activity-log.show');
});