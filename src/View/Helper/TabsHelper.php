<?php

namespace Bootstrap\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Class TabsHelper
 * @package Bootstrap\View\Helper
 *
 * @TODO Refactor with StringTemplater
 */
class TabsHelper extends Helper
{
    use ContentBlockHelperTrait {
        ContentBlockHelperTrait::start as startContent;
        ContentBlockHelperTrait::end as endContent;
        ContentBlockHelperTrait::clean as cleanContent;
    }

    public $helpers = ['Html', 'Url'];

    protected $_blockNamespace = 'tabs';
    protected $_tabs = [];
    protected $_tabId;

    public function create($options = [])
    {
        $this->_tabs = [];
        $this->_tabId = null;
    }

    /**
     * @param array $options
     * @deprecated Use create() instead.
     */
    public function start($options = []) {
        $this->create($options);
    }

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

    public function end()
    {
        if ($this->_tabId) {
            $this->_tabs[$this->_tabId]['content'] = $this->endContent();
            $this->_tabId = null;
        }
    }

    public function render()
    {
        $this->end();
        $this->cleanContent();

        $debugEnabled = Configure::read('debug');
        $tabs = "";
        $js = "";
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
                'id' => $tabMenuId
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