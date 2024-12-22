<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\Register_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('register' , Register_Controller::class);
Route::post('login', [LoginController::class , 'login']);

Route::middleware(['auth:api']) ->group(function(){
   Route::post('logout', LogoutController::class);
    Route::resource('order'   , OrdersController::class);
    Route::resource('products', ProductsController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('favorites', FavoritesController::class);
});
