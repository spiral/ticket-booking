<?php

declare(strict_types=1);

namespace App\UI\Web\Request;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Validation\Symfony\AttributesFilter;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ReserveTicketRequest extends AttributesFilter
{
    #[Post(key: 'screening_id')]
    #[NotBlank(message: 'The `screening_id` field must not be empty.')]
    public readonly int $screeningId;

    #[Post(key: 'reservation_type_id')]
    #[NotBlank(message: 'The `reservation_type_id` field must not be empty.')]
    public readonly int $reservationTypeId;

    #[Post(key: 'seat_ids')]
    #[NotBlank(message: 'The `seat_ids` field must not be empty.')]
    public readonly array $seatIds;
}
