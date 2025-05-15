<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\Routing\Router;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Class MenuHelper
 *
 * @package Bootstrap\View\Helper
 */
class MenuHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * @var array
     */
    public array $helpers = ['Html'];

    /**
     * Default config for this class
     *
     * @var array
     */
    protected array $_defaultConfig = [
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

    /**
     * @var array
     */
    protected $_menu;

    /**
     * @var null|callable
     */
    protected $_urlCallback = null;

    protected $_currentDepth = 0;

    /**
     * @param array $menu
     * @return $this
     */
    public function create(array $menu)
    {
        $menu += [
            'title' => null,
            'class' => null,
            'items' => null,
            'trail' => true,
            'active' => true,
            'template' => null,
            'templates' => [],
            'classes' => [],
            'maxDepth' => null
        ];

        $defaultClasses = [
            'menu' => '',
            'submenuItem' => '',
            'submenu' => '',
            'item' => '',
            'itemWithChildren' => '',
            'activeMenu' => 'active',
            'activeItem' => 'active',
            'trailMenu' => 'trail',
            'trailItem' => 'trail',
        ];
        $menu['classes'] = array_merge($defaultClasses, $menu['classes']);

        $this->_menu = $menu;
        if (!$this->_menu['class']) {
            $this->_menu['class'] = $this->_menu['classes']['menu'];
        }
        if ($this->_menu['maxDepth'] === null) {
            $this->_menu['maxDepth'] = -1;
        }

        if ($menu['templates']) {
            $this->templater()->add($menu['templates']);
        }

        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setUrlCallback(callable $callback)
    {
        $this->_urlCallback = $callback;

        return $this;
    }

    /**
     * @return null|string
     */
    public function render()
    {
        $this->_currentDepth = 0;
        if (!$this->_menu || !$this->_menu['items']) {
            return '';
        }

        return $this->_renderMenu($this->_menu);
    }

    /**
     * @param $menu
     * @return null|string
     */
    protected function _renderMenu($menu)
    {
        $title = null;
        if ($menu['title']) {
            $title = $this->templater()->format('navListTitle', ['content' => $menu['title']]);
        }

        $template = $menu['template'] ?: 'navList';

        return $this->templater()->format($template, [
            'class' => $menu['class'],
            'title' => $title,
            'items' => $this->_renderItems($menu['items']),
        ]);
    }

    /**
     * @param $items
     * @return string
     */
    protected function _renderItems($items)
    {
        $html = "";
        foreach ($items as $item) {
            try {
                $html .= $this->_renderItem($item);
            } catch (\Exception $ex) {
                debug($ex->getMessage());
            }
        }

        return $html;
    }

    /**
     * @param $item
     * @return null|string
     * @todo refactor with options argument to override option params per item
     */
    protected function _renderItem(/*MenuItem */$item)
    {
        //$item += ['title' => null, 'url' => null, 'attr' => []];

        // legacy support
        //if (empty($item['children']) && isset($item['_children'])) {
        //    $item['children'] = $item['_children'];
        //    unset($item['_children']);
        //}
        $template = 'navListItem';
        $hasChildren = isset($item['children']) && count($item['children']) > 0 ? true : false;

        $url = $this->_getItemUrl($item);
        $itemAttrs = $item['attr'] ?? [];

        //if (is_array($this->_menu['active'])) {
        //
        //    $isOnTrail = false; // @TODO check trail for
        //    $isActive = (in_array($item['id'], $this->_menu['active'])) ? true : false;

        //} else {
            $isOnTrail = $this->_menu['trail'] && $this->_isUrlOnTrail($url) ? true : false;
            $isActive = $this->_menu['active'] && $this->_isActiveUrl($url) ? true : false;
        //}

        $attrs = ['class' => $this->_menu['classes']['item']];

        if ($isOnTrail) {
            $attrs = $this->Html->addClass($attrs, $this->_menu['classes']['trailItem']);
        } elseif ($isActive) {
            $attrs = $this->Html->addClass($attrs, $this->_menu['classes']['activeItem']);
        }

        $submenu = null;
        $isMaxDepth = ($this->_menu['maxDepth'] >= 0 && $this->_currentDepth >= $this->_menu['maxDepth']);
        if ($hasChildren && !$isMaxDepth) {
            //$attrs = $this->Html->addClass($attrs, $this->_menu['classes']['submenuItem']);

            $itemAttrs = $this->Html->addClass($itemAttrs, $this->_menu['classes']['itemWithChildren']);
            // set the data-toggle attr by default. this is bootstrap specific.
            //$itemAttrs = $this->Html->addClass($itemAttrs, 'dropdown', 'data-toggle');

            // render the submenu
            $submenuTemplate = 'navSubmenuList'; // ($isOnTrail || $isActive) ? 'navSubmenuListTrail' : 'navSubmenuList';
            $_menu = [
                'title' => null,
                'class' => $this->_menu['classes']['submenu'],
                'items' => $item['children'],
                'template' => $submenuTemplate,
            ];

//            if ($isOnTrail) {
//                $_menu = $this->Html->addClass($_menu, $this->_menu['classes']['trailItem']);
//            }
//            if ($isActive) {
//                $_menu = $this->Html->addClass($_menu, $this->_menu['classes']['activeItem']);
//            }

            $this->_currentDepth++;
            $submenu = $this->_renderMenu($_menu);
            $this->_currentDepth--;

            $template = 'navListItemSubmenu';
            //debug($item['attr']);
        }

        //$class = $attrs['class'];
        //debug($class);
        //unset($attrs['class']);

        return $this->templater()->format($template, [
            //'class' => $class,
            'attrs' => $this->templater()->formatAttributes($attrs),
            'link' => $this->_renderLink($item, $itemAttrs),
            'submenu' => $submenu,
        ]);
    }

    /**
     * @param array $item
     * @param array $attrs
     * @return null|string
     */
    protected function _renderLink($item, $attrs = [])
    {
        return $this->templater()->format('navLink', [
            'url' => $this->Html->Url->build($this->_getItemUrl($item)),
            'attrs' => $this->templater()->formatAttributes($attrs),
            'content' => h($item['title']),
        ]);
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function _getItemUrl($item)
    {
        if ($this->_urlCallback && is_callable($this->_urlCallback)) {
            return call_user_func($this->_urlCallback, $item);
        }

        return $item['url'];
    }

    /**
     * @param $url
     * @return bool
     */
    protected function _isActiveUrl($url)
    {
        return Router::normalize($url) === Router::normalize($this->getView()->getRequest()->getPath());
    }

    /**
     * @param $url
     * @return bool
     */
    protected function _isUrlOnTrail($url)
    {
//        $request = $this->_View->request;
//        if (is_array($url)) {
//            if ($url['plugin'] == $request->getParam('plugin')) {
//                return true;
//            }
//        }
        return false;
    }
}
