<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\CenterPriceController;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route to display the exit form
Route::get('/vehicles/{id}/exit', [VehicleController::class, 'showExitForm'])->name('vehicle.exit');
// Route::get('/centers/create', function () {
//     return view('show.centers.create');
// });
// users list 
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
// Route::delete('/admin/vehicles/delete/{id}',[VehicleController::class,'destroy'] )->name('vehicle.destroy');
// Route::resource('/admin/vehicles',VehicleController::class);

Route::get('/check-uniqueness', [VehicleController::class, 'checkUniqueness'])->name('vehicle.checkUniqueness');
//// Route to submit the center form
Route::get('/centers/create', [CenterController::class, 'create'])->name('add.centers');
Route::post('/centers/store', [CenterController::class, 'store'])->name('centers.store');
//// Route to submit the center prices form
Route::get('/centers/prices/create', [CenterPriceController::class, 'create'])->name('centers.prices');

Route::post('/centers/prices/store', [CenterPriceController::class, 'store'])->name('centers.price.store');
// Route to submit the exit form
Route::put('/vehicles/{id}/exit', [VehicleController::class, 'submitExitForm'])->name('vehicle.submitExit');

Route::get('/vehicles/{id}/calculate-price', [VehicleController::class, 'calculatePrice'])->name('vehicle.calculatePrice');
Route::delete('/vehicle/image/{id}', [VehicleController::class, 'deleteImage'])->name('vehicle.image.delete');

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



