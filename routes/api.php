<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WasteApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/requests', [WasteApiController::class, 'getRequests']);
Route::get('/driver-locations', [WasteApiController::class, 'getDriverLocations']);
Route::get('/bin-status', [WasteApiController::class, 'getBinStatus']);
Route::post('/create-request', [WasteApiController::class, 'createRequest']);
