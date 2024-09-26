<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FirebaseUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReferencielController;




Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/token/refresh', [AuthController::class, 'refreshToken']);


});

Route::middleware('auth:api')->prefix('v1/auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});



// Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('/user/register', [UserController::class, 'register']);
    Route::get('/users', [UserController::class, 'index']);
    
    Route::patch('/users/{id}', [UserController::class, 'update']);


Route::get('/users/role/{roleName}', [UserController::class, 'getUsersByRole']);

    // referenceils

Route::post('/referentiels', [ReferencielController::class, 'store']);  

// });



