<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

use App\Utilities\GenericUtilities as GU;
use App\Services\GenericServices as GS;

use App\Models\RequestorModel;
use App\Models\PreparerModel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

Route::get('/login', function () {
	return view('auth.login');
})->name('login');

// Fixed Route for all new application that will use Auth
Route::get('/app-login/{id}', [AuthenticationController::class, 'app_login'])->name('app.login');
// Login Route
Route::post('/login', [LoginController::class, 'postLogin'])->name('login.post');

// Auth Middleware Group
Route::middleware('auth')->group(function() {
	// Main Session Check for Authetication
	Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
	// Dash/Dashboard
	Route::get('/', [DashboardController::class, 'dash'])->name('dash');


	// Landing Page
	Route::get('/home', function(){
		// {"DH_Module": true, "FA_Module": true, "RQ_Module": true, "HRA_Module": true, "HRP_Module": true}
		// $user = User::find(1); 
		// Auth::login($user);
		return view('home');
	})->name('home');

	// REQUESTOR
	Route::get('/requestor', function(){
		return view('panda.requestor');
	})->middleware('module.access:RQ');

	Route::get('/requestor-view', function(Request $request){
		$requestID = decrypt($request->requestID);
		return view('panda.requestor-view', compact('requestID'));
	})->middleware('module.access:RQ');


	// DIVISION HEAD
	Route::get('/divisionhead', function(){
		return view('panda.divisionhead');
	})->middleware('module.access:DH');

	Route::get('/divisionhead-view', function(Request $request){
		$requestID = decrypt($request->requestID);
		$panExist = PreparerModel::where('request_id', $requestID)->exists();

		return view('panda.divisionhead-view', compact('requestID', 'panExist'));
	})->middleware('module.access:DH');


	// HR PREPARER
	Route::get('/hrpreparer', function(){
		return view('panda.hrpreparer');
	})->middleware('module.access:HRP');

	Route::get('/hrpreparer-view', function(Request $request){
		$requestID = decrypt($request->requestID);
		return view('panda.hrpreparer-view', compact('requestID'));
	})->middleware('module.access:HRP');


	// HR APPROVER
	Route::get('/hrapprover', function(){
		return view('panda.hrapprover');
	})->middleware('module.access:HRA');

	Route::get('/hrapprover-view', function(Request $request){
		$requestID = decrypt($request->requestID);
		return view('panda.hrapprover-view', compact('requestID'));
	})->middleware('module.access:HRA');


	// FINAL APPROVER
	Route::get('/approver', function(){
		return view('panda.approver');
	})->middleware('module.access:FA');

	Route::get('/approver-view', function(Request $request){
		$requestID = decrypt($request->requestID);
		return view('panda.approver-view', compact('requestID'));
	})->middleware('module.access:FA');

	Route::get('testing', function(){
		return view('testing');
	});
});

// ADMIN
Route::get('/admin', function(){
	return view('admin.admin');
});


Route::get('/gs', function () {
	return GS::service1();
});


