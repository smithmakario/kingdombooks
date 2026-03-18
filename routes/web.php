<?php

use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/paystack/public-key', [PaystackController::class, 'publicKey'])
    ->name('paystack.public-key');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users/pending', [UserApprovalController::class, 'index'])->name('users.pending');
    Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');
});

Route::middleware(['auth', 'payments_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', function () {
        return view('orders-dashboard');
    })->name('orders.dashboard');
    Route::get('/paystack/transactions', [PaystackController::class, 'transactions'])->name('paystack.transactions');
});

require __DIR__.'/auth.php';
