<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Route\Route;

Route::get('/', HomeController::class, 'index');
Route::get('/categories/$id', CategoryController::class, 'show');
Route::get('/articles/$id', ArticleController::class, 'show');

Route::responseNotFound();
