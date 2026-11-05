<?php

use App\Controllers\HomeController;
use App\Route\Route;

Route::get('/', HomeController::class, 'index');

Route::responseNotFound();
