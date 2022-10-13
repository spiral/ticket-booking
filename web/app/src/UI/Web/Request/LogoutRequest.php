<?php

declare(strict_types=1);

namespace App\UI\Web\Request;

use Spiral\Filters\Attribute\Input\Input;
use Spiral\Validation\Symfony\AttributesFilter;
use Symfony\Component\Validator\Constraints\NotBlank;

final class LogoutRequest extends AttributesFilter
{
    #[Input(key: 'token')]
    #[NotBlank(message: 'The `token` field must not be empty.')]
    public readonly string $token;
}
