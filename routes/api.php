<?php

use App\Http\Controllers\Designs\DesignController;
use Illuminate\Support\Facades\Route;


if (env('APP_ENV') === 'prod') {
    \Illuminate\Support\Facades\URL::forceScheme('https');
}

// Public route
# Route::get('me','User\MeController@getMe');
Route::get('comments','Designs\CommentController@index');
// get designs
Route::get('designs',  'Designs\DesignController@index');
Route::get('designs/all',  'Designs\DesignController@allDesigns');
Route::get('designs/{id}',  'Designs\DesignController@findDesign');
Route::get('designs/slug/{slug}',  'Designs\DesignController@findBySlug');


//Search designs
Route::get('search/designs',  'Designs\DesignController@search');
Route::get('search/designers',  'User\UserController@search');

// get users
Route::get('users',  'User\UserController@index');
Route::get('user/{username}',  'User\UserController@findByUserName');
Route::get('users/{id}',  'User\UserController@findUser');
Route::get('users/{id}/designs',  'Designs\DesignController@getForUser');

// Teams
Route::get('teams/slug/{slug}','Teams\TeamController@findBySlug');
Route::get('teams/{id}/designs','Designs\DesignController@getForTeam');

Route::post('designs', 'Designs\UploadController@upload');
Route::get('image/{id}', 'Designs\UploadController@getImage');

// Route group for Authenticated users only
Route::group(['middleware' => ['auth:api']], function (){
    Route::post('logout', 'Auth\LoginController@logout');
    Route::put('settings/profile','User\SettingsController@updateProfile');
    Route::put('settings/password','User\SettingsController@updatePassword');
    Route::get('me','User\MeController@getMe');

    //upload Designs

    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::delete('designs/{id}', 'Designs\DesignController@destroy');

    //comments
    Route::post('designs/{id}/comments','Designs\CommentController@store');
    Route::put('comments/{id}','Designs\CommentController@update');
    Route::delete('comments/{id}','Designs\CommentController@destroy');

    // likes and Unlikes
    Route::post('designs/{id}/like','Designs\DesignController@like');
    Route::get('designs/{id}/liked','Designs\DesignController@checkIfUserHasLiked');

    // Teams
    Route::get('teams','Teams\TeamController@index');
    Route::post('teams','Teams\TeamController@store');
    Route::get('teams/{id}','Teams\TeamController@findById');
    Route::get('user/teams','Teams\TeamController@fetchUserTeams');
    Route::put('teams/{id}','Teams\TeamController@update');
    Route::delete('teams/{id}','Teams\TeamController@destroy');
    Route::delete('teams/{team_id}/user/{user_id}','Teams\TeamController@removeFromTeam');

    //invitations
    Route::get('invitations','Teams\InvitationController@index');
    Route::post('invitation/{teamId}','Teams\InvitationController@invite');
    Route::post('invitation/{id}/resend','Teams\InvitationController@resend');
    Route::post('invitation/{id}/respond','Teams\InvitationController@respond');
    Route::delete('invitations/{id}','Teams\InvitationController@destroy');

    //chats

    Route::post('chats','Chats\ChatController@sendMessage');
    Route::get('chats','Chats\ChatController@getUserChats');
    Route::get('chats/{id}/messages','Chats\ChatController@getChatMessages');
    Route::put('chats/{id}/markAsRead','Chats\ChatController@markAsRead');
    Route::delete('message/{id}','Chats\ChatController@destroyMessage');

});

// Route group for guests only
Route::group(['middleware' => ['guest:api']], function (){
    Route::post('register', 'Auth\RegisterController@register');
    Route::get('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset','Auth\ResetPasswordController@reset');

});




Route::get('posts', "API\PostAPIController@index");
Route::get('posts/{id}', "API\PostAPIController@show");
Route::put('posts/{id}', "API\PostAPIController@update");
Route::post('posts/{user_id}', "API\PostAPIController@store");
Route::delete('posts/{id}', "API\PostAPIController@destroy");
Route::delete('image/{user_id}/{post_id}', "Designs\UploadController@deleteImage");

