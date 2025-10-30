<?php

namespace App\Enums;

enum ReviewSource: string
{
    case Google = 'google';
    case Osm = 'osm';
    case Manual = 'manual';
}
