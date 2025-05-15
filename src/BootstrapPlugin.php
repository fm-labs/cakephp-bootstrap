<?php
declare(strict_types=1);

namespace Bootstrap;

use Cake\Core\BasePlugin;

/**
 * Plugin for Bootstrap
 */
class BootstrapPlugin extends BasePlugin
{
    /**
     * @var bool
     */
    public bool $routesEnabled = false;

    /**
     * @var bool
     */
    public bool $bootstrapEnabled = false;
}
