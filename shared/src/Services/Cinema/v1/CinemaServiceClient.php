<?php

declare(strict_types=1);

namespace Spiral\Shared\Services\Cinema\v1;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class CinemaServiceClient implements CinemaServiceInterface
{
	public function __construct(private InterceptableCore $core)
	{
	}


	public function Schedule(ContextInterface $ctx, DTO\ScheduleRequest $in): DTO\ScheduleResponse
	{
		[$response, $status] = $this->core->callAction(CinemaServiceInterface::class, '/'.self::NAME.'/Schedule', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Cinema\v1\DTO\ScheduleResponse::class,
		]);

		return $response;
	}


	public function Screening(ContextInterface $ctx, DTO\ScreeningRequest $in): DTO\ScreeningResponse
	{
		[$response, $status] = $this->core->callAction(CinemaServiceInterface::class, '/'.self::NAME.'/Screening', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Cinema\v1\DTO\ScreeningResponse::class,
		]);

		return $response;
	}


	public function CheckSeats(ContextInterface $ctx, DTO\CheckSeatsRequest $in): DTO\CheckSeatsResponse
	{
		[$response, $status] = $this->core->callAction(CinemaServiceInterface::class, '/'.self::NAME.'/CheckSeats', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Cinema\v1\DTO\CheckSeatsResponse::class,
		]);

		return $response;
	}


	public function Reserve(ContextInterface $ctx, DTO\ReserveRequest $in): DTO\ReserveResponse
	{
		[$response, $status] = $this->core->callAction(CinemaServiceInterface::class, '/'.self::NAME.'/Reserve', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Cinema\v1\DTO\ReserveResponse::class,
		]);

		return $response;
	}


	public function Reservations(ContextInterface $ctx, DTO\ReservationsRequest $in): DTO\ReservationsResponse
	{
		[$response, $status] = $this->core->callAction(CinemaServiceInterface::class, '/'.self::NAME.'/Reservations', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Cinema\v1\DTO\ReservationsResponse::class,
		]);

		return $response;
	}


	public function Cancel(ContextInterface $ctx, DTO\CancelRequest $in): DTO\CancelResponse
	{
		[$response, $status] = $this->core->callAction(CinemaServiceInterface::class, '/'.self::NAME.'/Cancel', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Cinema\v1\DTO\CancelResponse::class,
		]);

		return $response;
	}


	public function Purchase(ContextInterface $ctx, DTO\PurchaseRequest $in): DTO\PurchaseResponse
	{
		[$response, $status] = $this->core->callAction(CinemaServiceInterface::class, '/'.self::NAME.'/Purchase', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Cinema\v1\DTO\PurchaseResponse::class,
		]);

		return $response;
	}
}
