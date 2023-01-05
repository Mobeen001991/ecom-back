<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController; 
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductVarientController;
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

//routes/api.php




Route::post('register',[PassportAuthController::class,'registerUser']);
Route::post('login',[PassportAuthController::class,'loginUser']);
//add this middleware to ensure that every request is authenticated
Route::middleware('auth:api')->group(function(){
    Route::get('user', [PassportAuthController::class,'authenticatedUserDetails']);
    Route::get('product',[ProductController::class,'index']);
    Route::get('product/{id}',[ProductController::class,'show']);
    Route::post('product',[ProductController::class,'store']);
    Route::post('product/{id}',[ProductController::class,'update']);
    Route::delete('product/{id}',[ProductController::class,'destroy']);
    Route::post('product_variant',[ProductVarientController::class,'store']);
    Route::post('product_variant/{id}',[ProductVarientController::class,'update']);
    Route::get('product_variant/{id}',[ProductVarientController::class,'show']);
    Route::delete('product_variant/{id}',[ProductVarientController::class,'destroy']);
    // Route::middleware('auth:isAdmin')->group(function(){
       
    // });
});
// Route::fallback(function(){
//     return response()->json([
//         'message' => 'Invalid Request'], 404);
// });