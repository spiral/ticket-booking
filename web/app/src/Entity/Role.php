<?php

declare(strict_types=1);

namespace App\Entity;

enum Role: string
{
    case GUEST = 'ROLE_GUEST';
    case USER = 'ROLE_USER';
}
