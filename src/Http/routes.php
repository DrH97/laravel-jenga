<?php

use DrH\Jenga\Http\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('jenga/callbacks')
    ->name('jenga.callbacks.')
    ->group(function () {
        Route::post('ipn', [Controller::class, 'handleIpn'])->name('ipn');
        Route::post('bill-ipn', [Controller::class, 'handleBillIpn'])->name('bill_ipn');
    });
