<?php

namespace App\Enums;

enum Role: string
{
    case WRITER = 'writer';
    case SUPER_ADMIN = 'Super-Admin';
}
