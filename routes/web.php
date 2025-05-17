<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;

// Public routes
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('topic/{topic}', [TopicController::class, 'index'])->name('topic.show');
Route::get('subcategory/{id}', [SubcategoryController::class, 'index'])->name('subcategory.show');

// Authentication routes
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('verification', [VerificationController::class, 'index'])->name('verification');

// Admin routes
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Category management
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('create', [CategoryController::class, 'create'])->name('create');
        Route::delete('delete', [CategoryController::class, 'delete'])->name('delete');
        Route::put('update', [CategoryController::class, 'update'])->name('update');
    });

    // Subcategory management
    Route::prefix('subcategory')->name('subcategory.')->group(function () {
        Route::get('/', [SubcategoryController::class, 'admin'])->name('index');
        Route::post('create', [SubcategoryController::class, 'create'])->name('create');
        Route::delete('delete', [SubcategoryController::class, 'delete'])->name('delete');
        Route::put('update', [SubcategoryController::class, 'update'])->name('update');
    });
});

// Admin and moderator routes
Route::middleware(['role:admin,moderator'])->prefix('manage')->name('manage.')->group(function () {
    Route::get('topic', [TopicController::class, 'admin'])->name('topic.index');
    Route::get('message', [MessageController::class, 'index'])->name('message.index');
});

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Topic management
    Route::prefix('topic')->name('topic.')->group(function () {
        Route::post('create', [TopicController::class, 'create'])->name('create');
        Route::delete('delete', [TopicController::class, 'delete'])->name('delete');
        Route::put('update', [TopicController::class, 'update'])->name('update');
    });

    // Message management
    Route::prefix('message')->name('message.')->group(function () {
        Route::post('create', [MessageController::class, 'create'])->name('create');
        Route::delete('delete', [MessageController::class, 'delete'])->name('delete');
        Route::put('update', [MessageController::class, 'update'])->name('update');
    });

    // User management
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('profile', [UserController::class, 'index'])->name('profile');
        Route::delete('delete', [UserController::class, 'delete'])->name('delete');
        Route::post('verification', [UserController::class, 'verification'])->name('verification');
        Route::put('update', [UserController::class, 'update'])->name('update');
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
