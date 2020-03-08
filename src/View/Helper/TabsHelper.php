<?php

namespace Bootstrap\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Class TabsHelper
 * @package Bootstrap\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @TODO Refactor with StringTemplater
 */
class TabsHelper extends Helper
{
    use ContentBlockHelperTrait {
        ContentBlockHelperTrait::start as startContent;
        ContentBlockHelperTrait::end as endContent;
        ContentBlockHelperTrait::clean as cleanContent;
    }

    /**
     * @var array
     */
    public $helpers = ['Html', 'Url'];

    /**
     * @var string
     */
    protected $_blockNamespace = 'tabs';

    /**
     * @var array
     */
    protected $_tabs = [];

    /**
     * @var string
     */
    protected $_tabId;

    /**
     * @param array $options Tabs options
     * @return void
     */
    public function create($options = [])
    {
        $this->_tabs = [];
        $this->_tabId = null;
    }

    /**
     * @param array $options Tabs options
     * @return void
     * @deprecated Use create() instead.
     */
    public function start($options = [])
    {
        $this->create($options);
    }

    /**
     * Start a new tab block
     *
     * @param string $title Tab title
     * @param array $params Additional tab params
     * @return void
     */
    public function add($title, $params = [])
    {
        $params = array_merge(['title' => $title, 'url' => null, 'content' => null, 'debugOnly' => false], $params);

        if ($this->_tabId) {
            $this->end();
        }

        $this->_tabId = uniqid('tab');
        $this->_tabs[$this->_tabId] = $params;
        $this->startContent($this->_tabId);
    }

    /**
     * Close active tab block
     *
     * @return void
     */
    public function end()
    {
        if ($this->_tabId) {
            $this->_tabs[$this->_tabId]['content'] = $this->endContent();
            $this->_tabId = null;
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->end();
        $this->cleanContent();

        $debugEnabled = Configure::read('debug');
        $tabs = "";
        //$js = "";
        $menuItems = "";

        // render tab menu
        $menuClass = "nav nav-tabs";
        foreach ($this->_tabs as $tabId => $item) {
            if ($item['debugOnly'] === true && $debugEnabled !== true) {
                continue;
            }

            $tabMenuId = $tabId . '-menu';
            $href = '#' . $tabId;

            // build tab link
            $tabLinkAttrs = [
                'role' => 'presentation',
                'id' => $tabMenuId,
            ];

            if ($item['url']) {
                $tabLinkAttrs['data-url'] = $this->Url->build($item['url'], true);
                //$tabLinkAttrs['data-target'] = $tabId;
            }
            $tabLink = $this->Html->link($item['title'], $href, $tabLinkAttrs);

            // build tab menu item
            $menuItems .= $this->Html->tag('li', $tabLink, ['role' => 'tab', 'aria-controls' => $tabId]);

            //$js .= sprintf("$('#%s').tab(%s); ", $tabMenuId, json_encode($tabParams));
        }
        $menu = $this->Html->tag('ul', $menuItems, ['class' => $menuClass, 'role' => 'tablist']);

        // render tab contents
        $tabClass = "tab-pane";
        $i = 0;
        foreach ($this->_tabs as $tabId => $item) {
            $class = ($i++ > 0) ? $tabClass : $tabClass . " active";

            $attrs = ['id' => $tabId, 'role' => 'tabpanel'];
            //if ($item['url']) {
            //    $attrs['data-tab-url'] = $this->Url->build($item['url']);
            //}

            $tabs .= $this->Html->div($class, $item['content'], $attrs);
        }
        $tabs = $this->Html->div('tab-content', $tabs);

        //$script = sprintf("$(document).ready(function() { %s });", $js);
        $script = "";

        return $this->Html->div('tabs', $menu . $tabs . $this->Html->scriptBlock($script, ['safe' => false]));
    }
}
