<?php

use App\Http\Controllers\Api\LegacyPeekController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Dev-tool bridge for the v2 "Legacy Peek" comparison panel — see
// storage/app/legacy-export/legacy-peek-tool-plan.md. Read-only, gated by a
// shared-secret header, not the org's real auth system.
Route::middleware('legacy_peek.key')
    ->get('/legacy-peek/employee/{company_id}', [LegacyPeekController::class, 'employee']);
