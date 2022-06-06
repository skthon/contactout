<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});


/**
 * Authentication Routes
 *      Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
 *      Route::post('login', 'Auth\LoginController@login');
 *      Route::post('logout', 'Auth\LoginController@logout')->name('logout');
 *
 * Registration Routes
 *      Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
 *      Route::post('register', 'Auth\RegisterController@register');
 *
 * Password Reset Routes
 *      Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
 *      Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
 *      Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
 *      Route::post('password/reset', 'Auth\ResetPasswordController@reset');
 */
Auth::routes();


 /**
  * User routes
  */
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');
});


/**
 * Admin routes
 */
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/referrals', [App\Http\Controllers\Admin\AdminController::class, 'referrals'])
        ->name('admin-referrals');
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])
        ->name('admin-users');
});
