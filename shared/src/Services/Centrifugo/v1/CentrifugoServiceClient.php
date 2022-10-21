<?php

declare(strict_types=1);

namespace Spiral\Shared\Services\Centrifugo\v1;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class CentrifugoServiceClient implements CentrifugoServiceInterface
{
	public function __construct(private InterceptableCore $core)
	{
	}


	public function Publish(ContextInterface $ctx, DTO\PublishRequest $in): DTO\PublishResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Publish', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\PublishResponse::class,
		]);

		return $response;
	}


	public function Broadcast(ContextInterface $ctx, DTO\BroadcastRequest $in): DTO\BroadcastResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Broadcast', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\BroadcastResponse::class,
		]);

		return $response;
	}


	public function Subscribe(ContextInterface $ctx, DTO\SubscribeRequest $in): DTO\SubscribeResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Subscribe', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\SubscribeResponse::class,
		]);

		return $response;
	}


	public function Unsubscribe(ContextInterface $ctx, DTO\UnsubscribeRequest $in): DTO\UnsubscribeResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Unsubscribe', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\UnsubscribeResponse::class,
		]);

		return $response;
	}


	public function Disconnect(ContextInterface $ctx, DTO\DisconnectRequest $in): DTO\DisconnectResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Disconnect', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\DisconnectResponse::class,
		]);

		return $response;
	}


	public function Presence(ContextInterface $ctx, DTO\PresenceRequest $in): DTO\PresenceResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Presence', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\PresenceResponse::class,
		]);

		return $response;
	}


	public function PresenceStats(ContextInterface $ctx, DTO\PresenceStatsRequest $in): DTO\PresenceStatsResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/PresenceStats', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\PresenceStatsResponse::class,
		]);

		return $response;
	}


	public function History(ContextInterface $ctx, DTO\HistoryRequest $in): DTO\HistoryResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/History', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\HistoryResponse::class,
		]);

		return $response;
	}


	public function HistoryRemove(ContextInterface $ctx, DTO\HistoryRemoveRequest $in): DTO\HistoryRemoveResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/HistoryRemove', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\HistoryRemoveResponse::class,
		]);

		return $response;
	}


	public function Info(ContextInterface $ctx, DTO\InfoRequest $in): DTO\InfoResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Info', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\InfoResponse::class,
		]);

		return $response;
	}


	public function RPC(ContextInterface $ctx, DTO\RPCRequest $in): DTO\RPCResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/RPC', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\RPCResponse::class,
		]);

		return $response;
	}


	public function Refresh(ContextInterface $ctx, DTO\RefreshRequest $in): DTO\RefreshResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Refresh', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\RefreshResponse::class,
		]);

		return $response;
	}


	public function Channels(ContextInterface $ctx, DTO\ChannelsRequest $in): DTO\ChannelsResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Channels', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\ChannelsResponse::class,
		]);

		return $response;
	}


	public function Connections(ContextInterface $ctx, DTO\ConnectionsRequest $in): DTO\ConnectionsResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/Connections', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\ConnectionsResponse::class,
		]);

		return $response;
	}


	public function UpdateUserStatus(ContextInterface $ctx, DTO\UpdateUserStatusRequest $in): DTO\UpdateUserStatusResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/UpdateUserStatus', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\UpdateUserStatusResponse::class,
		]);

		return $response;
	}


	public function GetUserStatus(ContextInterface $ctx, DTO\GetUserStatusRequest $in): DTO\GetUserStatusResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/GetUserStatus', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\GetUserStatusResponse::class,
		]);

		return $response;
	}


	public function DeleteUserStatus(ContextInterface $ctx, DTO\DeleteUserStatusRequest $in): DTO\DeleteUserStatusResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/DeleteUserStatus', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\DeleteUserStatusResponse::class,
		]);

		return $response;
	}


	public function BlockUser(ContextInterface $ctx, DTO\BlockUserRequest $in): DTO\BlockUserResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/BlockUser', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\BlockUserResponse::class,
		]);

		return $response;
	}


	public function UnblockUser(ContextInterface $ctx, DTO\UnblockUserRequest $in): DTO\UnblockUserResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/UnblockUser', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\UnblockUserResponse::class,
		]);

		return $response;
	}


	public function RevokeToken(ContextInterface $ctx, DTO\RevokeTokenRequest $in): DTO\RevokeTokenResponse
	{
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/RevokeToken', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\RevokeTokenResponse::class,
		]);

		return $response;
	}


	public function InvalidateUserTokens(
		ContextInterface $ctx,
		DTO\InvalidateUserTokensRequest $in,
	): DTO\InvalidateUserTokensResponse {
		[$response, $status] = $this->core->callAction(CentrifugoServiceInterface::class, '/'.self::NAME.'/InvalidateUserTokens', [
		    'in' => $in,
		    'ctx' => $ctx,
		    'responseClass' => \Spiral\Shared\Services\Centrifugo\v1\DTO\InvalidateUserTokensResponse::class,
		]);

		return $response;
	}
}
