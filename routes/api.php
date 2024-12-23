<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\Register_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;



Route::post('register' , Register_Controller::class);
Route::post('login', [LoginController::class , 'login']);

Route::middleware(['auth:api']) ->group(function(){
        Route::post('/update-profile', [RegisteredUserController::class, 'updateProfile']);

   Route::post('logout', LogoutController::class);
    Route::resource('order'   , OrdersController::class);
    Route::resource('products', ProductsController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('favorites', FavoritesController::class);

// routes/api.php
Route::get('/stores', [StoreController::class, 'index']); // Get all stores and their products
Route::get('/stores/{id}', [StoreController::class, 'show']); // Get a single store and its products
Route::get('/stores/{storeId}/products', [StoreController::class, 'products']); // Get products from a store





});
