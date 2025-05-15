<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\Helper;

/**
 * Bootstrap helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Bootstrap\View\Helper\FormHelper $Form
 */
abstract class BaseBootstrapHelper extends Helper
{
    public array $helpers = ['Html'/*, 'Form' => ['className' => 'Bootstrap.Form']*/];

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
