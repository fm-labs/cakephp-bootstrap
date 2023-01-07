<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\Helper;

/**
 * Bootstrap helper
 */
class BootstrapHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'scriptUrl' => 'Bootstrap.bootstrap3/bootstrap.min',
        'scriptBlock' => true,
        'cssUrl' => 'Bootstrap.bootstrap3/bootstrap.min',
        'cssBlock' => true,
    ];

    public $helpers = ['Html', 'Form' => ['className' => 'Bootstrap.Form']];

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        $this->_View->Html->css($this->getConfig('cssUrl'),
            ['block' => $this->getConfig('cssBlock')]);

        $this->_View->Html->script($this->getConfig('scriptUrl'),
            ['block' => $this->getConfig('scriptBlock')]);
    }
}
