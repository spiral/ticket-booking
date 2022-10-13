<?php

declare(strict_types=1);

namespace Spiral\Shared\Services\Payment\v1;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class PaymentServiceClient implements PaymentServiceInterface
{
	public function __construct(private InterceptableCore $core)
	{
	}


	public function Charge(ContextInterface $ctx, DTO\ChargeRequest $in): DTO\ChargeResponse
	{
		[$response, $status] = $this->core->callAction(PaymentServiceInterface::class, '/'.self::NAME.'/Charge', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Payment\v1\DTO\ChargeResponse::class,
		]);

		return $response;
	}
}
