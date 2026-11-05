<?php

namespace App\Domain\Articles\Enum;

enum Sort: string
{
    case DATE = 'date';
    case VIEWS = 'views';
}
