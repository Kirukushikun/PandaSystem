<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

use App\Utilities\GenericUtilities as GU;
use App\Services\GenericServices as GS;

use App\Models\RequestorModel;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Fixed Route for all new application that will use Auth
Route::get('/app-login/{id}', [AuthenticationController::class, 'app_login'])->name('app.login');
// Login Route
Route::post('/login', [LoginController::class, 'postLogin'])->name('login.post');

// // Auth Middleware Group
// Route::middleware('auth')->group(function() {
// 	// Main Session Check for Authetication
// 	Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
// 	// Dash/Dashboard
// 	Route::get('/', [DashboardController::class, 'dash'])->name('dash');

// });

Route::get('/gs', function () {
	return GS::service1();
});

Route::get('/login', function () {
	return view('auth.login');
});

// Landing Page
Route::get('/home', function(){
	// {"DH_Module": true, "FA_Module": true, "RQ_Module": true, "HRA_Module": true, "HRP_Module": true}
	$user = User::find(1); 
	Auth::login($user);
	return view('home');
});



Route::get('/requestor', function(){
	return view('panda.requestor');
});

Route::get('/divisionhead', function(){
	return view('panda.divisionhead');
});

Route::get('/preparer', function(){
	return view('panda.preparer');
});

Route::get('/approver', function(){
	return view('panda.approver');
});

// Viewing Entry Page
Route::get('/request-view', function(Request $request){
	$requestID = $request->query('requestID');
	$request = RequestorModel::findOrFail($requestID);
	return view('panda.request-view', compact('request'));
});

Route::get('/requestor-view', function(Request $request){
	$requestID = $request->query('requestID');
	$request = RequestorModel::findOrFail($requestID);
	return view('panda.requestor-view', compact('request'));
});

Route::get('/preparer-view', function(Request $request){
	$requestID = $request->query('requestID');
	return view('panda.preparer-view', compact('requestID'));
});

Route::get('/approver-view', function(Request $request){
	$requestID = $request->query('requestID');
	return view('panda.approver-view', compact('requestID'));
});

// Admin
Route::get('/admin', function(){
	return view('admin.admin');
});

Route::get('testing', function(){
	return view('testing');
});
