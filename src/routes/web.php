<?php

use App\Controllers\UserController;
use App\Route\Route;

Route::get('/$id', UserController::class, 'show');

Route::responseNotFound();
