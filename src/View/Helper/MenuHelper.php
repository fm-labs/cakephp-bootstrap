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

class MenuHelper extends Helper
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
            'navLink' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
            'navList' => '{{title}}<ul class="{{class}}">{{items}}</ul>',
            'navListItem' => '<li role="presentation"{{attrs}}>{{link}}</li>',
            'navListTitle' => '<h4>{{content}}</h4>',
            'navListItemSubmenu' => '<li role="presentation"{{attrs}}>{{link}}{{submenu}}</li>',
            'navSubmenuList' => '<ul class="{{class}}"{{attrs}}>{{items}}</ul>',
            'navSubmenuListTrail' => '<ul class="nav nav-pills nav-stacked nav-nested trail">{{items}}</ul>',
        ],
    ];

    protected $_menu;

    public function create(array $menu)
    {
        $menu += ['title' => null, 'class' => null, 'items' => null, 'trail' => true, 'active' => true, 'template' => null, 'templates' => [], 'classes' => []];

        $defaultClasses =  [
            'menu' => '',
            'submenuItem' => '',
            'submenu' => '',
            'item' => '',
            'activeMenu' => 'active',
            'activeItem' => 'active',
            'trailMenu' => 'trail',
            'trailItem' => 'trail'
        ];
        $menu['classes'] = array_merge($defaultClasses, $menu['classes']);

        $this->_menu = $menu;
        if (!$this->_menu['class']) {
            $this->_menu['class'] = $this->_menu['classes']['menu'];
        }

        if ($menu['templates']) {
            $this->templater()->add($menu['templates']);
        }

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

        $title = null;
        if ($menu['title']) {
            $title = $this->templater()->format('navListTitle', ['content' => $menu['title']]);
        }

        $template = ($menu['template']) ?: 'navList';
        return $this->templater()->format($template, [
            'class' => $menu['class'],
            'title' => $title,
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

        $isOnTrail = ($this->_menu['trail'] && $this->_isUrlOnTrail($item['url'])) ? true : false;
        $isActive = ($this->_menu['active'] && $this->_isActiveUrl($item['url'])) ? true : false;

        $attrs = ['class' => $this->_menu['classes']['item']];
        if ($isOnTrail) {
            $attrs = $this->Html->addClass($attrs, $this->_menu['classes']['trailItem']);
        }

        if ($isActive) {
            $attrs = $this->Html->addClass($attrs, $this->_menu['classes']['activeItem']);
        }

        $submenu = null;
        if ($item['children']) {
            $submenuTemplate = 'navSubmenuList'; // ($isOnTrail || $isActive) ? 'navSubmenuListTrail' : 'navSubmenuList';
            $_menu = ['title' => null, 'class' => $this->_menu['classes']['submenu'], 'items' => $item['children'], 'template' => $submenuTemplate];

            if ($isOnTrail) {
                $_menu = $this->Html->addClass($_menu, $this->_menu['classes']['trailItem']);
            }
            if ($isActive) {
                $_menu = $this->Html->addClass($_menu, $this->_menu['classes']['activeItem']);
            }

            $submenu = $this->_renderMenu($_menu);

            $template = 'navListItemSubmenu';
        }

        $class = $attrs['class'];
        unset($attrs['class']);

        return $this->templater()->format($template, [
            'class' => $class,
            'attrs' => $this->templater()->formatAttributes($attrs),
            'link' => $this->_renderLink($item),
            'submenu' => $submenu,
        ]);
    }

    protected function _renderLink(&$item)
    {
        $item['attr'] = (isset($item['attr'])) ? $item['attr'] : [];

        return $this->templater()->format('navLink', [
            'url' => $this->Html->Url->build($item['url']),
            'attrs' => $this->templater()->formatAttributes($item['attr']),
            'content' => h($item['title']),
        ]);
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