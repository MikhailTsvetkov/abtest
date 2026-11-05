<?php

declare(strict_types=1);

use App\Config\Config;
use App\ServiceProviders\Database\Contracts\DbServiceProvider;

// Optional configuration providers, applied in order after services.yml.
// Example: new App\ServiceProviders\ApplicationServiceProvider(),
return [
    new DbServiceProvider(new Config()),
];
