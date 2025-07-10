<?php

use App\Models\Room;
use App\Models\Image;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\ReservationController;


//PUBLIC
Route::controller(PublicPageController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/rooms', 'rooms')->middleware('honeypot')->name('rooms.index');
    Route::get('/rooms/{room}', 'showRoom')->name('rooms.show');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::view('/contact', 'pages.contact')->name('contact');
    Route::post('/contact', 'sendContact')->middleware('honeypot')->name('contact.send');
});

Route::post('/rooms/{room}/book', [ReservationController::class, 'storeUser'])
    ->middleware(['throttle:5,1', 'honeypot'])
    ->name('reservations.store');

//API
Route::prefix('api')->group(function () {
    Route::get('/rooms/{room}/booked', [RoomController::class, 'reservationRoom']);
});

//ADMIN
Route::prefix('admin')->middleware('auth')->group(function () {

    Route::controller(RoomController::class)->group(function () {
        Route::get('/rooms', 'index')->name('admin.rooms.index');
        Route::get('/rooms/create', 'create')->name('admin.rooms.create');
        Route::get('/rooms/{room}/edit', 'edit')->name('admin.rooms.edit');
        Route::delete('/rooms/{room}', 'destroy')->name('admin.rooms.destroy');
    });

    Route::controller(ReservationController::class)->group(function () {
        Route::get('/reservations', 'index')->name('admin.reservations.index');
        Route::get('/reservations/create', 'create')->name('admin.reservations.create');
        Route::post('/reservations', 'store')->name('admin.reservations.store');
        Route::get('/reservations/{reservation}/edit', 'edit')->name('admin.reservations.edit');
        Route::put('/reservations/{reservation}', 'update')->name('admin.reservations.update');
    });

    Route::delete('/logout', [SessionController::class, 'destroy'])->name('logout');
});

Route::get('/admin/login', [SessionController::class, 'create'])
    ->middleware('honeypot')
    ->name('login');
Route::post('/admin/login', [SessionController::class, 'store']);
Route::redirect('/admin', '/admin/rooms');
