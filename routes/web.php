<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// ROUTE CRUD CUSTOMER 
Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
Route::post('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');

// ROUTE AUTHENTICATION & DASHBOARD (Hanya untuk tamu)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'createRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'storeRegister'])->name('auth.register.store');
    Route::get('/login', [AuthController::class, 'createLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'storeLogin'])->name('auth.login.store');
});

// ROUTE TERPROTEKSI (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
