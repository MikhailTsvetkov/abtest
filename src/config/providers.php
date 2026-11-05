<?php

declare(strict_types=1);

use App\Config\Config;
use App\ServiceProviders\Database\Contracts\DbServiceProvider;
use App\ServiceProviders\Domain\Articles\Contracts\ArticlesStorageServiceProvider;
use App\ServiceProviders\Domain\Categories\Contracts\CategoriesStorageServiceProvider;
use App\ServiceProviders\SmartyServiceProvider;

// Optional configuration providers, applied in order after services.yml.
// Example: new App\ServiceProviders\ApplicationServiceProvider(),
return [
    new DbServiceProvider(new Config()),
    new CategoriesStorageServiceProvider(),
    new ArticlesStorageServiceProvider(),
    new SmartyServiceProvider(),
];
