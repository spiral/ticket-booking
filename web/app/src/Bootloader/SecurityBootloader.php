<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\Entity\Role;
use App\Security\ActorProvider;
use App\Security\CentrifugoTokenTransport;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Auth\AuthBootloader;
use Spiral\Bootloader\Auth\HttpAuthBootloader;
use Spiral\Security\PermissionsInterface;
use Spiral\Security\Rule\AllowRule;

final class SecurityBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        AuthBootloader::class,
    ];

    public function init(
        HttpAuthBootloader $authBootloader,
        AuthBootloader $auth,
        PermissionsInterface $permissions
    ): void
    {
        $authBootloader->addTransport('centrifugo', new CentrifugoTokenTransport());

        $auth->addActorProvider(ActorProvider::class);

        $permissions->addRole(Role::GUEST->value);
        $permissions->addRole(Role::USER->value);

        $permissions->associate(Role::USER->value, 'personal.*', AllowRule::class);
        $permissions->associate(Role::USER->value, 'personal.*.*', AllowRule::class);
    }
}
