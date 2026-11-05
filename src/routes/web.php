<?php

use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Controllers\ArticleController;
use App\Route\Route;

Route::get('/', HomeController::class, 'index');
Route::get('/categories/$id', CategoryController::class, 'show');
Route::get('/articles/$id', ArticleController::class, 'show');

Route::responseNotFound();
