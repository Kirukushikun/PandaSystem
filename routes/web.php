<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

use App\Utilities\GenericUtilities as GU;
use App\Services\GenericServices as GS;

// Fixed Route for all new application that will use Auth
Route::get('/app-login/{id}', [AuthenticationController::class, 'app_login'])->name('app.login');
// Login Route
Route::get('/login', [LoginController::class, 'login'])->name('login');
// Auth Middleware Group
Route::middleware('auth')->group(function() {
	// Main Session Check for Authetication
	Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
	// Dash/Dashboard
	Route::get('/', [DashboardController::class, 'dash'])->name('dash');

});

Route::get('/gs', function () {
	return GS::service1();
});

// Landing Page
Route::get('/requestor', function(){
	return view('panda.requestor');
});

Route::get('/preparer', function(){
	return view('panda.preparer');
});

// Viewing Entry Page

Route::get('/preparer-view', function(){
	return view('panda.preparer-view');
});

Route::get('testing', function(){
	return view('testing');
});