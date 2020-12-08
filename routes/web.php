<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Request;
use App\Models\User;

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

//Route::get('/', [App\Http\Controllers\MainController::class])->only(['index', 'Home']);
//

Route::get('/', [\App\Http\Controllers\MainController::class, 'index']);



Route::middleware(['check.admin'])->group(function () {
    //category
    Route::get('category', '\App\Http\Controllers\CategoryController@index');
    Route::post('category/create', '\App\Http\Controllers\CategoryController@create');
    Route::delete('category/delete', '\App\Http\Controllers\CategoryController@delete');
    Route::put('category/update', '\App\Http\Controllers\CategoryController@update');

    //subcategory

    Route::post('subcategory/create', '\App\Http\Controllers\SubcategoryController@create');
    Route::delete('subcategory/delete', '\App\Http\Controllers\SubcategoryController@delete');
    Route::put('subcategory/update', '\App\Http\Controllers\SubcategoryController@update');
});

Route::get('subcategory/{id}', '\App\Http\Controllers\SubcategoryController@index');




//topic
Route::get('topic/{id}', '\App\Http\Controllers\TopicController@index');
Route::post('topic/create', '\App\Http\Controllers\TopicController@create');
Route::delete('topic/delete', '\App\Http\Controllers\TopicController@delete');
Route::put('topic/update', '\App\Http\Controllers\TopicController@update');


//message
//Route::get('message', '\App\Http\Controllers\MessageController@index');
Route::post('message/create', '\App\Http\Controllers\MessageController@create');
Route::delete('message/delete', '\App\Http\Controllers\MessageController@delete');
Route::put('message/update', '\App\Http\Controllers\MessageController@update');


//user
Route::put('user/update', '\App\Http\Controllers\UserController@update');
Route::delete('user/delete', '\App\Http\Controllers\UserController@delete');











Route::middleware(['check.auth', 'loginregister'])->group(function () {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
});

Route::middleware(['loginregister'])->group(function () {
    Route::get('login', 'App\Http\Controllers\AuthController@login');
    Route::get('register', 'App\Http\Controllers\AuthController@register');
});




Route::get('verification', '\App\Http\Controllers\VerificationController@index');
Route::get('logout', 'App\Http\Controllers\AuthController@logout');








