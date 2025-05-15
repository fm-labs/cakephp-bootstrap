<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Class TabsHelper
 *
 * @package Bootstrap\View\Helper
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
    public array $helpers = ['Html', 'Url'];

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
    public function add($title, $content = null, $params = [])
    {
        if (is_array($content)) {
            $params = $content;
            $content = null;
        }

        $params = array_merge(['title' => $title, 'url' => null, 'content' => null, 'debugOnly' => false], $params);

        if ($this->_tabId) {
            $this->end();
        }

        $this->_tabId = uniqid('t');
        $this->_tabs[$this->_tabId] = $params;
        $this->startContent($this->_tabId);

        if ($content != null) {
            echo $content;
            $this->end();
        }
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
        $domId = uniqid('tabs');

        // render tab menu
        $menuClass = "nav nav-tabs";
        $i = 0;
        foreach ($this->_tabs as $tabId => $item) {
            if ($item['debugOnly'] === true && $debugEnabled !== true) {
                continue;
            }

            $class = "nav-link";
            $selected = "false";
            if ($i++ === 0) {
                $class .= ' active';
                $selected = "true";
            }

            $tabLinkId = $tabId . '-tab';
            $tabPaneId = $tabId . '-pane';
            $href = '#' . $tabId;

            // build tab link
            $tabLinkAttrs = [
                'role' => 'tab',
                'id' => $tabLinkId,
                'data-bs-toggle' => 'tab',
                'data-bs-target' => '#' . $tabPaneId,
                'type' => 'button',
                'aria-controls' => $tabPaneId,
                'aria-selected' => $selected,
                'class' => $class,
            ];

            if ($item['url']) {
                $tabLinkAttrs['data-url'] = $this->Url->build($item['url'], ['fullBase' => true]);
                //$tabLinkAttrs['data-target'] = $tabId;
            }
            //$tabLink = $this->Html->link($item['title'], $href, $tabLinkAttrs);
            //     <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Home</button>
            $tabLink = $this->Html->tag('button', $item['title'], $tabLinkAttrs);

            // build tab menu item
            $menuItems .= $this->Html->tag('li', $tabLink, ['class' => 'nav-item', 'role' => 'presentation']);

            //$tabParams = [];
            //$js .= sprintf("$('#%s').tab(%s); ", $tabMenuId, json_encode($tabParams));
        }
        $menuList = $this->Html->tag('ul', $menuItems, ['class' => $menuClass, 'role' => 'tablist']);
        $menuContainer = $this->Html->tag('div', $menuList/*, ['class' => 'container']*/);
        $menu = $this->Html->tag('div', $menuContainer, ['class' => 'tab-nav']);

        // render tab contents
        $tabClass = "tab-pane";
        $i = 0;
        foreach ($this->_tabs as $tabId => $item) {
            $tabLinkId = $tabId . '-tab';
            $tabPaneId = $tabId . '-pane';
            $class = $tabClass . " fade";
            if ($i++ === 0) {
                $class .= ' show active';
            }

            $attrs = ['id' => $tabPaneId, 'role' => 'tabpanel'];
            $content = $item['content'] ?? 'NO-CONTENT';
            if ($item['url']) {
                //$attrs['data-tab-url'] = $this->Url->build($item['url']);
                $url = $this->Url->build($item['url']);
                $content = sprintf("Loading content from %s ...", $url);
            }

            $tabs .= $this->Html->div($class, $content, $attrs);
        }
        $tabs = $this->Html->div('tab-content', $tabs);

        //$script = sprintf("$(document).ready(function() { %s });", $js);
        //$script = "";
        $scriptTemplate = <<<SCRIPT
    $('#%s .tab-nav a').on('click', function (ev) {

        var tabLink = $(ev.target);
        var url = tabLink.attr("data-url");

        //AdminJs.Console.log('tabs nav link clicked: ' + this.hash, url);

        if (typeof url !== "undefined" && !tabLink.hasClass('tab-loaded') && !tabLink.hasClass('tab-loading')) {
            var target = this.hash;
            var tab = $(target);
            if (!tab.length) {
                console.error("Tab content area with ID " + target + " not found");
                return;
            }

            // ajax load from data-url
            tab.html("Loading ...");
            tabLink.addClass('tab-loading').tab('show');
            var jqxhr = $.get(url, {}, function() {
                console.log("tab loading success");
            })
            .fail(function() {
                console.error("Tab load failed");
                tab.html("Failed to load tab");
            })
            .done(function(data) {
                console.log("Tab " + target + " loaded");
                tab.html(data);
                //tabLink.tab('show');
            })
            .then(function() {
                console.log("After loading");
                tabLink.addClass('tab-loaded');
                tabLink.removeClass('tab-loading');
            });
        } else {
            tabLink.tab('show');
        }

        tabLink.closest('.tabs').addClass('tabs-init');
        ev.preventDefault();
        ev.stopPropagation();
        return false;
    });
SCRIPT;
        //$script = sprintf($scriptTemplate, $domId);
        //$this->Html->scriptBlock($script, ['safe' => false, 'block' => true]);

        return $this->Html->div('tabs', $menu . $tabs, ['id' => $domId]);
    }
}
