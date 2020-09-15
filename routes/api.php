<?php

use App\Http\Controllers\Designs\DesignController;
use Illuminate\Support\Facades\Route;

// Public route
# Route::get('me','User\MeController@getMe');

// get designs
Route::get('designs',  'Designs\DesignController@index');
Route::get('designs/{id}',  'Designs\DesignController@findDesign');

// get users
Route::get('users',  'User\UserController@index');
Route::get('users/{id}',  'User\UserController@findUser');

// Route group for Authenticated users only
Route::group(['middleware' => ['auth:api']], function (){
    Route::post('logout', 'Auth\LoginController@logout');
    Route::put('settings/profile','User\SettingsController@updateProfile');
    Route::put('settings/password','User\SettingsController@updatePassword');
    Route::get('me','User\MeController@getMe');

    //upload Designs
    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::delete('designs/{id}', 'Designs\DesignController@destroy');

});

// Route group for guests only
Route::group(['middleware' => ['guest:api']], function (){
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset','Auth\ResetPasswordController@reset');

});
