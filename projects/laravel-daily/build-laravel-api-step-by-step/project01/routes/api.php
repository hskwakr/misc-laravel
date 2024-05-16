<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth;

Route::post('auth/register', Auth\RegisterController::class);
