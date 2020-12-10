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


Route::get('/', [\App\Http\Controllers\MainController::class, 'index']);



Route::middleware(['check.admin'])->group(function () {
    //category
    Route::post('category/create', '\App\Http\Controllers\CategoryController@create');
    Route::delete('category/delete', '\App\Http\Controllers\CategoryController@delete');
    Route::put('category/update', '\App\Http\Controllers\CategoryController@update');
    //subcategory
    Route::post('subcategory/create', '\App\Http\Controllers\SubcategoryController@create');
    Route::delete('subcategory/delete', '\App\Http\Controllers\SubcategoryController@delete');
    Route::put('subcategory/update', '\App\Http\Controllers\SubcategoryController@update');
    //admin panel

    Route::get('subcategory', '\App\Http\Controllers\SubcategoryController@admin');
    Route::get('category', '\App\Http\Controllers\CategoryController@index');
});

Route::middleware(['admin.moder'])->group(function () {
    Route::get('topic', '\App\Http\Controllers\TopicController@admin');
    Route::get('message', '\App\Http\Controllers\MessageController@index');
});




Route::middleware(['check.auth'])->group(function () {
    //topic
    Route::post('topic/create', '\App\Http\Controllers\TopicController@create');
    Route::delete('topic/delete', '\App\Http\Controllers\TopicController@delete');
    Route::put('topic/update', '\App\Http\Controllers\TopicController@update');
//message
    Route::post('message/create', '\App\Http\Controllers\MessageController@create');
    Route::delete('message/delete', '\App\Http\Controllers\MessageController@delete');
    Route::put('message/update', '\App\Http\Controllers\MessageController@update');
//user
    Route::get('user/profile', '\App\Http\Controllers\UserController@index');

    Route::delete('user/delete', '\App\Http\Controllers\UserController@delete');

    Route::post('user/verification', '\App\Http\Controllers\UserController@verification');

    Route::get('logout', 'App\Http\Controllers\AuthController@logout');

    Route::put('user/update', '\App\Http\Controllers\UserController@update');
});


Route::get('topic/{id}', '\App\Http\Controllers\TopicController@index');
Route::get('subcategory/{id}', '\App\Http\Controllers\SubcategoryController@index');


Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('register', 'App\Http\Controllers\AuthController@register');
Route::get('verification', '\App\Http\Controllers\VerificationController@index');





