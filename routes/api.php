<?php

// Public route

// Route group for Authenticated users only
Route::group(['middleware' => ['auth:api']], function (){

});

// Route group for guests only
Route::group(['middleware' => ['guest:api']], function (){
    Route::post('register', 'Auth\RegisterController@register');
});
