<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OutOfServiceController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Tag_ItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
// Route::post('/register',[AuthController::class,'register'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    //login
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/verify', [AuthController::class, 'verify']);
    Route::post('/follow',[AuthController::class,'follow']);
    Route::get('/get/user',[AuthController::class,'get']);
    Route::post('/owner_search',[AuthController::class,'owner_search']);

    //Verification
    Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

    //Tag
    Route::post('/add/tag_name',[TagController::class,'store']);
    Route::get('/get/tag',[TagController::class,'get']);
    Route::post('/edit/tag',[TagController::class,'edit']);

    //Tag_Item
    Route::post('/add/tag_item',[Tag_ItemController::class,'storeTag_item']);
    Route::get('/get/item/tag',[Tag_ItemController::class,'get_item_tag']);

    //Item
    Route::post('/add/item',[ItemController::class,'store']);
    // Route::get('/get/item',[ItemController::class,'get']);
    Route::post('/edit/item',[ItemController::class,'edit']);

    //Out_of_service
    Route::get('/get/out_of_service',[OutOfServiceController::class,'get']);
    Route::post('/add/out_of_service',[OutOfServiceController::class,'store']);

    //Booking
    Route::post('/add/booking',[BookingController::class,'store']);
    Route::get('/get/booking',[BookingController::class,'get']);
    Route::get('/get/booking_item',[BookingController::class,'getBookingItem']);
    Route::post('/approve/booking',[BookingController::class,'approve']);
    Route::get('/get/item/amount',[BookingController::class,'booking_item_amount']);
    
});

Route::get('/delete/booking',[BookingController::class,'delete']);