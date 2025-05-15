<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;


/**
 * Bootstrap helper
 */
class Bootstrap3Helper extends BaseBootstrapHelper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $_defaultConfig = [
        'scriptUrl' => 'Bootstrap.bootstrap3/bootstrap.min',
        'scriptBlock' => true,
        'cssUrl' => 'Bootstrap.bootstrap3/bootstrap.min',
        'cssBlock' => true,
    ];
}
