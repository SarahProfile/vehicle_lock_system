<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route to display the exit form
Route::get('/vehicles/{id}/exit', [VehicleController::class, 'showExitForm'])->name('vehicle.exit');

Route::get('/check-uniqueness', [VehicleController::class, 'checkUniqueness'])->name('vehicle.checkUniqueness');

// Route to submit the exit form
Route::put('/vehicles/{id}/exit', [VehicleController::class, 'submitExitForm'])->name('vehicle.submitExit');

Route::get('/vehicles/{id}/calculate-price', [VehicleController::class, 'calculatePrice'])->name('vehicle.calculatePrice');

Route::prefix('admin')->group(function () {
    Route::get('home', [VehicleController::class, 'index'])->name('vehicle.search');
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [VehicleController::class, 'index']);
        Route::get('/create', [VehicleController::class, 'create']);
        Route::post('/store', [VehicleController::class, 'store'])->name('vehicle.add');
        Route::get('/{id}/edit', [VehicleController::class, 'edit'])->name('vehicle.edit');
        Route::post('/{id}/update', [VehicleController::class, 'update'])->name('vehicle.update');
        Route::get('/delete/{id}', [VehicleController::class, 'destroy'])->name('vehicle.destroy');
        Route::get('/vehicle/{id}', [VehicleController::class, 'show'])->name('vehicle.show');
        Route::get('/{id}', [VehicleController::class, 'showFull'])->name('vehicle.showFull');

  



    });
});
