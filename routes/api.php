<?php

use App\Http\Controllers\Auth\Admin\AppSettingsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

use App\Http\Controllers\PriceTrackerController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

if (env('APP_ENV') === 'production') {
    \Illuminate\Support\Facades\URL::forceScheme('https');
}

// Public route
# Route::get('me','User\MeController@getMe');
Route::get('all_comments', 'Designs\CommentController@index');
// get designs
Route::get('designs', 'Designs\DesignController@index');
Route::get('designs/all', 'Designs\DesignController@allDesigns');
Route::get('designs/{id}', 'Designs\DesignController@findDesign');
Route::get('designs/slug/{slug}', 'Designs\DesignController@findBySlug');


//Search designs
Route::get('search/designs', 'Designs\DesignController@search');
Route::get('search/designers', 'User\UserController@search');

// get users
Route::get('users', 'User\UserController@index');
Route::get('user/{username}', 'User\UserController@findByUserName');
Route::get('users/{id}', [UserController::class, 'findUser']);
Route::get('users/{id}/designs', 'Designs\DesignController@getForUser');

// Teams
Route::get('teams/slug/{slug}', 'Teams\TeamController@findBySlug');
Route::get('teams/{id}/designs', 'Designs\DesignController@getForTeam');

Route::post('designs', 'Designs\UploadController@upload');
Route::get('image/{id}', 'Designs\UploadController@getImage');

// Route group for Authenticated users only
Route::group(['middleware' => ['auth:api']], function () {

    // Route group for Admin only
    Route::group(['middleware' => [
         'auth:admin',
         'admin'
    ], 'prefix' => 'admin'], function () {

        // App Settings
        Route::delete('settings/user/{email}', [SettingsController::class, 'deleteUser']);

        Route::post('settings/clear/{model}', [AppSettingsController::class, 'index']);
        Route::get('settings/models', [AppSettingsController::class, 'getModels']);
        Route::post('settings/model/populate/{model}', [AppSettingsController::class, 'populateData']);
        Route::post('settings/types', [AppSettingsController::class, 'createLikeTypes']);
        Route::get('settings/types', [AppSettingsController::class, 'getTypes']);
        Route::post('settings/reset/db', [AppSettingsController::class, 'resetDB']);
        Route::post('settings/export', [AppSettingsController::class, 'export']);
    });

    Route::post('logout', 'Auth\LoginController@logout');
    Route::put('settings/profile', 'User\SettingsController@updateProfile');
    Route::put('settings/password', 'User\SettingsController@updatePassword');
    Route::get('me', 'User\MeController@getMe');

    Route::post('avatar', [SettingsController::class, 'updateAvatar']);
    Route::delete('avatar', [SettingsController::class, 'deleteAvatar']);

    //upload Designs

    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::delete('designs/{id}', 'Designs\DesignController@destroy');

    //comments
    Route::post('designs/{id}/comments', 'Designs\CommentController@store');
    Route::put('comments/{id}', 'Designs\CommentController@update');
    Route::delete('comments/{id}', 'Designs\CommentController@destroy');

    // likes and Unlikes
    Route::post('designs/{id}/like', 'Designs\DesignController@like');
    Route::get('designs/{id}/liked', 'Designs\DesignController@checkIfUserHasLiked');

    // Teams
    Route::get('teams', 'Teams\TeamController@index');
    Route::post('teams', 'Teams\TeamController@store');
    Route::get('teams/{id}', 'Teams\TeamController@findById');
    Route::get('user/teams', 'Teams\TeamController@fetchUserTeams');
    Route::put('teams/{id}', 'Teams\TeamController@update');
    Route::delete('teams/{id}', 'Teams\TeamController@destroy');
    Route::delete('teams/{team_id}/user/{user_id}', 'Teams\TeamController@removeFromTeam');

    //invitations
    Route::get('invitations', 'Teams\InvitationController@index');
    Route::post('invitation/{teamId}', 'Teams\InvitationController@invite');
    Route::post('invitation/{id}/resend', 'Teams\InvitationController@resend');
    Route::post('invitation/{id}/respond', 'Teams\InvitationController@respond');
    Route::delete('invitations/{id}', 'Teams\InvitationController@destroy');

    //chats

    Route::post('chats', 'Chats\ChatController@sendMessage');
    Route::get('chats', 'Chats\ChatController@getUserChats');
    Route::get('chats/{id}/messages', 'Chats\ChatController@getChatMessages');
    Route::put('chats/{id}/markAsRead', 'Chats\ChatController@markAsRead');
    Route::delete('message/{id}', 'Chats\ChatController@destroyMessage');


    // Likes using Love Reacter Package
    Route::post('posts/like/{post_id}', [PostController::class, 'toggleLike']);

    // Comments using the Commentable Package
    Route::post('posts/comment/{post_id}', [PostController::class, 'addComment']);
    Route::post('comments/comment/{comment_id}', [CommentController::class, 'reply']);
    Route::post('comments/comment/react/{comment_id}', [CommentController::class, 'reactComment']);

    // FOLLOW USING FOLLOW PACKAGE
   // Route::post('user/follow/{author_id}',  'User\UserController@follow');
    Route::post('user/follow/{author_id}', [UserController::class, 'follow']);

    //Posts
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{id}', [PostController::class, 'update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);

    // Comments
    Route::get('comments/{comment_id}', [CommentController::class, 'index']);
});


// Route group for guests only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
    Route::get('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});


// Unauthenticated Post
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{id}', [PostController::class, 'show']);

// Unauthenticated Comments
Route::get('comments/{comment_id}', [CommentController::class, 'index']);


// Route group for Weather
Route::prefix('weather')->group(function () {
    Route::get('/', [WeatherController::class, 'index']);
    Route::get('/fetch/{city}', [WeatherController::class, 'store']);
    Route::get('/forecast/{city}', [WeatherController::class, 'forecast']);
    Route::get('/update/{city}', [WeatherController::class, 'update']);
    Route::post('/location', [WeatherController::class, 'myLocation']);
    Route::get('/peak', [WeatherController::class, 'peak']);
});



// Route group for PriceTracker
Route::prefix('tracker')->group(function () {
    Route::post('/', [PriceTrackerController::class, 'index']);
    Route::get('/check/{article}', [PriceTrackerController::class, 'check']);
});
