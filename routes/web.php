<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function (){

    Route::controller(\App\Http\Controllers\MikrotikController::class)
        ->name('mikrotik.')->group(function (){
            Route::get('mikrotik/router','index')
                ->name('index');
            Route::get('mikrotik/router/add','create')
                ->name('create');
            Route::post('mikrotik/router/add','store');
            Route::get('mikrotik/router/{mikrotik:slug}','show')->name('show');

            Route::get('mikrotik/router/{mikrotik:slug}/edit','edit')
                ->name('edit');
            Route::put('mikrotik/router/{mikrotik:slug}/edit','update');

            Route::delete('mikrotik/router/{mikrotik:slug}/delete','destroy')
                ->name('delete');

     });

    Route::controller(\App\Http\Controllers\RouterOsController::class)
        ->name('router.')->group(function (){
        Route::get('mikrotik/router/hotspot/profile/{mikrotik:slug}','getProfileHotspot')
            ->name('hotspot.profile');
        Route::get('mikrotik/router/hotspot/active/{mikrotik:slug}','hotspotActive')
            ->name('hotspot.active');
            Route::get('mikrotik/router/hotspot/user/{mikrotik:slug}','hotspotUser')
                ->name('hotspot.user');
            Route::get('mikrotik/router/ppp/secret/{mikrotik:slug}','pppSecret')
                ->name('ppp.secret');
            Route::get('mikrotik/router/ppp/active/{mikrotik:slug}','pppActive')
                ->name('ppp.active');
    });

    Route::controller(\App\Http\Controllers\HotspotController::class)
        ->name('hotspot.')->group(function (){
            Route::get('mikrotik/router/hotspot/{mikrotik:slug}','index')
                ->name('index');
            Route::post('mikrotik/router/user/profile/add/{mikrotik:slug}','createUserProfile')
                ->name('user.profile.add');
            Route::get('mikrotik/router/user/profile/{mikrotik:slug}','getProfile')
                ->name('profile');
        });
});
Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
