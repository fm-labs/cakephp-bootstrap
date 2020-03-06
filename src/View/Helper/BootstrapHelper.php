<?php
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
    protected $_defaultConfig = [];

    public $helpers = ['Html', 'Form' => ['className' => 'Bootstrap.Form']];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        $this->_View->Html->css('Bootstrap.bootstrap.min', ['block' => true]);
        $this->_View->Html->script('Bootstrap.bootstrap.min', ['block' => true]);
    }
}