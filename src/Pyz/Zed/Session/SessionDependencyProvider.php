<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Session;

use Spryker\Zed\Session\SessionDependencyProvider as SprykerSessionDependencyProvider;
use Spryker\Zed\SessionRedis\Communication\Plugin\Session\SessionHandlerRedisLockingPlugin;
use Spryker\Zed\SessionRedis\Communication\Plugin\Session\SessionHandlerRedisPlugin;
use Spryker\Zed\SessionRedis\Communication\Plugin\Session\SessionRedisLockReleaserPlugin;

class SessionDependencyProvider extends SprykerSessionDependencyProvider
{
    /**
     * @return \Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerPluginInterface[]
     */
    protected function getSessionHandlerPlugins(): array
    {
        return [
            new SessionHandlerRedisPlugin(),
            new SessionHandlerRedisLockingPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface[]
     */
    protected function getSessionLockReleaserPlugins(): array
    {
        return [
            new SessionRedisLockReleaserPlugin(),
        ];
    }
}
