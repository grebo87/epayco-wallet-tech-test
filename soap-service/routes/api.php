<?php

use App\Http\Controllers\WalletSoapController;
use Illuminate\Support\Facades\Route;

Route::any('/soap/wallet', [WalletSoapController::class, 'handleSoapRequest'])->name('soap.wallet');
