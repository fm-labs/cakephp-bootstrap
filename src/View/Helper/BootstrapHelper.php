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
class BootstrapHelper extends Bootstrap5Helper
{
    public array $helpers = [
        'Html',
        'Form' => ['className' => 'Bootstrap.Form'],
//        'Bootstrap.Button',
//        'Bootstrap.Dropdown',
//        'Bootstrap.Form',
//        'Bootstrap.Icon',
//        'Bootstrap.Menu',
//        'Bootstrap.Navbar',
//        'Bootstrap.Nav',
//        'Bootstrap.Panel',
//        'Bootstrap.Tabs',
//        'Bootstrap.Ui'
    ];
}
