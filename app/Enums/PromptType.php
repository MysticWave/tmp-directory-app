<?php

namespace App\Enums;

enum PromptType: string
{
    case AGGREGATE = 'aggregate';
    case FILTER = 'filter';
}
