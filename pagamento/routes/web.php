<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayPalController;

Route::get('/', [PayPalController::class, 'index'])->name('paypal.index');

// Criar a ordem PayPal
Route::post('/create', [PayPalController::class, 'create'])->name('paypal.create');

// Capturar o pagamento
Route::post('/complete', [PayPalController::class, 'complete'])->name('paypal.complete');

// Listar pagamentos (opcional)
Route::get('/payments', [PayPalController::class, 'listPayments'])->name('payments.list');
