<?php

declare(strict_types=1);

namespace App\UI\Web\Request;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Validation\Symfony\AttributesFilter;
use Symfony\Component\Validator\Constraints\NotBlank;

final class BuyRequest extends AttributesFilter
{
    #[Post(key: 'reservation_id')]
    #[NotBlank(message: 'The `reservation_id` field must not be empty.')]
    public readonly string $reservationId;
}
