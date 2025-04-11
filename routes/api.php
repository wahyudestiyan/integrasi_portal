<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('gitlab/webhook', [WebhookController::class, 'handle']);