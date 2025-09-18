<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::post('/sample', [ApiController::class, 'sampleEndpoint']);
