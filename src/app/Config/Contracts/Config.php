<?php

namespace App\Config\Contracts;

interface Config
{
    public function get(string $key);
}
