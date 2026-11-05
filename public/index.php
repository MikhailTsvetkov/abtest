<?php

require_once __DIR__ . '/../bootstrap.php';

/** @var $container ContainerBuilder */

use App\Controllers\UserController;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$userController = $container->get(UserController::class);

echo '<pre>';
print_r($userController->show(1));
echo '</pre>';
