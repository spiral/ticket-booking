<?php

declare(strict_types=1);

namespace App\Specification;

use App\Exception\EntityNotFoundException;
use App\Repository\ReservationRepositoryInterface;
use Ramsey\Uuid\UuidInterface;
use Spiral\Translator\TranslatorInterface;

final class ReservationIsExistsSpecification extends AbstractSpecification
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function isExists(UuidInterface $uuid): bool
    {
        return $this->isSatisfiedBy($uuid);
    }

    /**
     * @psalm-param UuidInterface $value
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy(mixed $value): bool
    {
        if (!$this->reservationRepository->hasByPK($value->toString())) {
            throw new EntityNotFoundException(
                $this->translator->trans('Reservation with UUID {uuid} not found.', ['uuid' => $value])
            );
        }

        return true;
    }
}
