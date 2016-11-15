<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 11/6/16
 * Time: 7:09 PM
 */

namespace Bootstrap\View\Helper;


use Cake\Routing\Router;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

class NavHelper extends Helper
{
    public $helpers = ['Html'];

    use StringTemplateTrait;

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'navList' => '<ul class="{{class}}">{{items}}</ul>',
            'navListItem' => '<li role="presentation"{{attrs}}>{{link}}</li>',
            'navListItemSubmenu' => '<li role="presentation"{{attrs}}>{{link}}{{submenu}}</li>',
        ],
    ];

    protected $_menu;

    public function create(array $menu)
    {
        $menu += ['class' => null, 'items' => null, 'trail' => true, 'active' => true];
        $this->_menu = $menu;

        return $this;
    }

    public function render()
    {
        if (!$this->_menu || !$this->_menu['items']) {
            return 'No Menu';
        }

        return $this->_renderMenu($this->_menu);
    }

    protected function _renderMenu($menu)
    {
        $menu += ['class' => null, 'items' => null];
        return $this->templater()->format('navList', [
            'class' => 'nav nav-pills nav-stacked nav-nested',
            'items' => $this->_renderItems($menu['items'])
        ]);
    }

    protected function _renderItems(&$items)
    {
        $html = "";
        foreach ($items as $item) {
            $html .= $this->_renderItem($item);
        }
        return $html;
    }

    protected function _renderItem(&$item)
    {
        $item += ['title' => null, 'url' => null, 'children' => null];

        // legacy support
        if (empty($item['children']) && isset($item['_children'])) {
            $item['children'] = $item['_children'];
            unset($item['_children']);
        }

        $template = 'navListItem';
        $submenu = '';

        if ($item['children']) {
            $template = 'navListItemSubmenu';
            $submenu = $this->_renderMenu(['items' => $item['children']]);
        }

        $attrs = ['class' => null];

        if ($this->_menu['trail'] && $this->_isUrlOnTrail($item['url'])) {
            $attrs = $this->Html->addClass($attrs, 'trail');
        }

        if ($this->_menu['active'] && $this->_isActiveUrl($item['url'])) {
            $attrs = $this->Html->addClass($attrs, 'active');
        }

        return $this->templater()->format($template, [
            'attrs' => $this->templater()->formatAttributes($attrs),
            'link' => $this->_renderLink($item),
            'submenu' => $submenu,
        ]);
    }

    protected function _renderLink(&$item)
    {
        $linkAttrs = $item;
        unset($linkAttrs['url']);
        unset($linkAttrs['children']);

        return $this->Html->link($item['title'], $item['url'], $linkAttrs);
    }

    protected function _isActiveUrl($url)
    {
        if (is_array($url)) {
            $url += ['plugin' => null, 'controller' => null, 'action' => 'index'];

            $request =& $this->_View->request;

            if ($url['plugin'] == $request['plugin'] && $url['controller'] == $request['controller'] && $url['action'] == $request['action']) {
                return true;
            }
        }

        return (Router::normalize($url) === Router::normalize($this->_View->request->url));
    }

    protected function _isUrlOnTrail($url)
    {
        if (!is_array($url)) {
            return false;
        }

        $url += ['plugin' => null, 'controller' => null, 'action' => null];
        $request =& $this->_View->request;

        if ($url['plugin'] == $request['plugin'] && $url['controller'] == $request['controller'] && $url['action'] == $request['action']) {
            return true;
        }
        elseif ($url['plugin'] == $request['plugin'] && $url['controller'] == $request['controller']) {
            return true;
        }
        elseif ($url['plugin'] == $request['plugin']) {
            return true;
        }

        return false;
    }
}