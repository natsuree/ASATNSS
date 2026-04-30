<?php

use App\Http\Controllers\ApiValidationController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/tally', [WebhookController::class, 'tally'])->name('webhooks.tally');
Route::post('/validate-email', [ApiValidationController::class, 'email'])->name('api.validate-email');
