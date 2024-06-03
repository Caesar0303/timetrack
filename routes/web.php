<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/my_shift_cards', [\App\Http\Controllers\ShiftCardController::class, 'my_cards'])->name('my_cards');
Route::get('/my_shift_cards/start/{id}', [\App\Http\Controllers\ShiftCardController::class, 'start_shift']);
Route::get('/my_shift_cards/end/{id}', [\App\Http\Controllers\ShiftCardController::class, 'end_shift']);
Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'employees'])->name('employees');
Route::get('/admin/delete/{id}', [\App\Http\Controllers\EmployeeController::class, 'delete']);
Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'admin_page'])->name('admin_page');
Route::get('/admin/add_employee', [\App\Http\Controllers\AdminController::class, 'add_employee'])->name('add_employee');
Route::post('/store_employee', [\App\Http\Controllers\AdminController::class, 'store_employee'])->name('store_employee');
Route::get('/admin/employee_edit/{id}', [\App\Http\Controllers\AdminController::class, 'employee_edit']);
Route::post('/admin/employee_update/{id}', [\App\Http\Controllers\AdminController::class, 'employee_update']);
Route::get('/show/employee/{id}', [\App\Http\Controllers\AdminController::class, 'show_employee']);
Route::get('/shift/edit/{id}', [\App\Http\Controllers\ShiftCardController::class, 'add_shift'])->name('add_shift');
Route::post('/shift/store/{id}', [\App\Http\Controllers\ShiftCardController::class, 'store']);
Route::get('/shift/delete/{id}/user/{user_id}', [\App\Http\Controllers\ShiftCardController::class, 'delete']);
Route::get('/records', [\App\Http\Controllers\RecordsController::class, 'list']);
Route::get('/records/{id}', [\App\Http\Controllers\AdminController::class, 'list']);
Route::get('/record/{id}', [\App\Http\Controllers\RecordsController::class, 'show']);




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ShiftCardController::class, 'index'])->name('dashboard');
});
