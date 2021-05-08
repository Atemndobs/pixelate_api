<?php

use App\Http\Controllers\Auth\Admin\ConfigurationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
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
Route::get('/start', function () {
    return view('start');
});

Auth::routes();



Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['web'])->group(function () {
    Route::get('/settings', [ConfigurationController::class, 'index'])->name('settings');
    Route::get('/settings/create', [ConfigurationController::class, 'create'])->name('settings.create');
    Route::post('/settings/store', [ConfigurationController::class, 'store'])->name('settings.store');
    Route::get('/settings/{group}', [ConfigurationController::class, 'create'])->name('settings.item');
    Route::delete('/settings/delete/{id}', [ConfigurationController::class, 'delete'])->name('settings.destroy');
    Route::get('/settings/show/{group}', [ConfigurationController::class, 'show'])->name('settings.show');
    Route::put('/settings/edit/{id}', [ConfigurationController::class, 'edit'])->name('settings.edit');

});


