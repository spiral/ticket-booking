<?php

declare(strict_types=1);

namespace Spiral\Shared\Services\Tokens\v1;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class TokensServiceClient implements TokensServiceInterface
{
	public function __construct(private InterceptableCore $core)
	{
	}


	public function Create(ContextInterface $ctx, DTO\CreateRequest $in): DTO\CreateResponse
	{
		[$response, $status] = $this->core->callAction(TokensServiceInterface::class, '/'.self::NAME.'/Create', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Tokens\v1\DTO\CreateResponse::class,
		]);

		return $response;
	}


	public function Load(ContextInterface $ctx, DTO\LoadRequest $in): DTO\LoadResponse
	{
		[$response, $status] = $this->core->callAction(TokensServiceInterface::class, '/'.self::NAME.'/Load', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Tokens\v1\DTO\LoadResponse::class,
		]);

		return $response;
	}


	public function Delete(ContextInterface $ctx, DTO\DeleteRequest $in): DTO\DeleteResponse
	{
		[$response, $status] = $this->core->callAction(TokensServiceInterface::class, '/'.self::NAME.'/Delete', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Tokens\v1\DTO\DeleteResponse::class,
		]);

		return $response;
	}
}
