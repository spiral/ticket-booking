<?php

declare(strict_types=1);

namespace Spiral\Shared\Services\Users\v1;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class UsersServiceClient implements UsersServiceInterface
{
	public function __construct(private InterceptableCore $core)
	{
	}


	public function Auth(ContextInterface $ctx, DTO\AuthRequest $in): DTO\AuthResponse
	{
		[$response, $status] = $this->core->callAction(UsersServiceInterface::class, '/'.self::NAME.'/Auth', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Users\v1\DTO\AuthResponse::class,
		]);

		return $response;
	}


	public function Register(ContextInterface $ctx, DTO\RegisterRequest $in): DTO\RegisterResponse
	{
		[$response, $status] = $this->core->callAction(UsersServiceInterface::class, '/'.self::NAME.'/Register', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Users\v1\DTO\RegisterResponse::class,
		]);

		return $response;
	}


	public function Get(ContextInterface $ctx, DTO\GetRequest $in): DTO\GetResponse
	{
		[$response, $status] = $this->core->callAction(UsersServiceInterface::class, '/'.self::NAME.'/Get', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Users\v1\DTO\GetResponse::class,
		]);

		return $response;
	}
}
