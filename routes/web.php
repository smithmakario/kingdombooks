<?php

use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\PaystackLogController;
use App\Http\Controllers\Admin\PendingDeliveryController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/paystack/public-key', [PaystackController::class, 'publicKey'])
    ->name('paystack.public-key');
Route::post('/payments/confirm', [ReceiptController::class, 'confirm'])->name('payments.confirm');
Route::get('/receipt/{reference}', [ReceiptController::class, 'show'])->name('receipts.show');
Route::get('/receipt/{reference}/download', [ReceiptController::class, 'download'])->name('receipts.download');

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
    Route::get('/paystack/logs', [PaystackLogController::class, 'index'])->name('paystack.logs');
});

Route::middleware(['auth', 'payments_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', function () {
        return view('orders-dashboard');
    })->name('orders.dashboard');
    Route::get('/paystack/transactions', [PaystackController::class, 'transactions'])->name('paystack.transactions');
    Route::get('/deliveries/pending', [PendingDeliveryController::class, 'index'])->name('deliveries.pending');
});

require __DIR__.'/auth.php';
