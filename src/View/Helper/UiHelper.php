<?php
namespace Bootstrap\View\Helper;

use Banana\Menu\MenuItem;
use Cake\Datasource\EntityTrait;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Class UiHelper
 *
 * @package Backend\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 * @property \Bootstrap\View\Helper\FormHelper $Form
 * @property \Bootstrap\View\Helper\ButtonHelper $Button
 * @property \Bootstrap\View\Helper\IconHelper $Icon
 * @property \Bootstrap\View\Helper\LabelHelper $Label
 */
class UiHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * @var array
     */
    public $helpers = ['Html', 'Url', 'Bootstrap.Form', 'Bootstrap.Button', 'Bootstrap.Icon', 'Bootstrap.Label'];

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'modal' => '<div class="modal"></div>',
            'menu' => '<ul{{attrs}}>{{items}}</ul>',
            'menuItem' => '<li{{attrs}}>{{content}}</li>',
            'menuItemDropdown' => '<li class="dropdown"{{attrs}}>{{content}}{{children}}</li>',
            'menuDropdownButton' => '<a{{attrs}}>{{title}} <span class="caret"></span></a>',
            'menuLink' => '<a{{attrs}}>{{title}}</a>',
            //'button' => '<button{{attrs}}>{{content}}</button>',
        ]
    ];

    /**
     * @param $title
     * @param $url
     * @param array $options
     * @return string
     */
    public function button($title, $url, array $options = [])
    {
        return $this->Button->link($title, $url, $options);
    }

    /**
     * @param $title
     * @param null $url
     * @param array $options
     * @return string
     */
    public function link($title, $url = null, array $options = [])
    {
        if (isset($options['icon'])) {
            $title = $this->icon($options['icon']) . " " . $title;

            $options['escape'] = false;
            unset($options['icon']);
        }

        return $this->Html->link($title, $url, $options);
    }

    /**
     * @param $title
     * @param null $url
     * @param array $options
     * @return string
     */
    public function postLink($title, $url = null, array $options = [])
    {
        if (isset($options['icon'])) {
            $title = $this->icon($options['icon']) . " " . $title;

            $options['escape'] = false;
            unset($options['icon']);
        }

        return $this->Form->postLink($title, $url, $options);
    }

    /**
     * @param $title
     * @param null $url
     * @param array $options
     * @return string
     */
    public function deleteLink($title, $url = null, array $options = [])
    {
        return $this->postLink($title, $url, $options);
    }

    /**
     * @param $status
     * @param array $options
     * @param array $map
     * @return null|string
     */
    public function statusLabel($status, $options = [], $map = [])
    {
        return $this->Label->status($status, $options, $map);
    }

    /**
     * @param $class
     * @param array $options
     * @return null|string
     */
    public function icon($class, $options = [])
    {
        return $this->Icon->create($class, $options);
    }

    /**
     * @param array $menuList
     * @param array $menuOptions
     * @param array $childMenuOptions
     * @param array $itemOptions
     * @return null|string
     */
    public function menu($menuList = [], $menuOptions = [], $childMenuOptions = [], $itemOptions = [])
    {
        $menuOptions += [
            'class' => null,
            'itemscope' => 'itemscope',
            'itemtype' => 'http://www.schema.org/SiteNavigationElement'
        ];

        $items = "";

        foreach ($menuList as $alias => $item) {
            if (is_object($item) && ($item instanceof EntityTrait || $item instanceof MenuItem)) {
                $item = $item->toArray();
            }

            $items .= $this->menuItem($item, $childMenuOptions, $itemOptions);
        }

        // build list
        return $this->templater()->format('menu', [
            'items' => $items,
            'attrs' => $this->templater()->formatAttributes($menuOptions),
        ]);
    }

    /**
     * @param array $item
     * @param array $childMenuOptions
     * @param array $itemOptions
     * @return null|string
     */
    public function menuItem(array $item = [], array $childMenuOptions = [], array $itemOptions = [])
    {
        $item += ['url' => null, 'children' => [], 'title' => null, 'class' => null, 'hide_in_nav' => null];

        // workaround
        if ($item['hide_in_nav']) {
            return '';
        }

        $url = $item['url'];
        unset($item['url']);

        if (isset($item['view_url'])) {
            $url = $item['view_url'];
            unset($item['view_url']);
        }
        $children = (isset($item['children'])) ? $item['children'] : [];
        unset($item['children']);

        // legacy support
        // _children is now children
        if (empty($children) && isset($item['_children'])) {
            $children = $item['_children'];
            unset($item['_children']);
        }

        $item['title'] = ($item['title']) ?: $this->Url->build($url);
        $item['itemprop'] = 'url';

        if (isset($item['attr'])) {
            $itemAttr = $item['attr'];
            unset($item['attr']);
            $item = array_merge($item, $itemAttr);
        }

        // build item
        if (!empty($children)) {
            $ddAttrs = [
                //'data-toggle' => ($url) ? "dropdown disabled" : "drowdown",
                'data-toggle' => 'dropdown',
                'role' => "button",
                'aria-haspopup' => "true",
                'aria-expanded' => "false",
                'href' => '#',
                'data-href' => ($url) ? $this->Url->build($url) : null,
            ];
            $ddAttrs += $item;
            $ddLink = $this->templater()->format('menuDropdownButton', [
                'attrs' => $this->templater()->formatAttributes($ddAttrs, ['requireRoot', 'data-icon']),
                'title' => $item['title']
            ]);

            $link = $ddLink;
            $tag = 'menuItemDropdown';
            $children = $this->menu($children, $childMenuOptions, $childMenuOptions, $itemOptions);
        } else {
            $link = $this->link($item['title'], $url, $item);
            $tag = 'menuItem';
            $children = null;
        }

        return $this->templater()->format($tag, [
            'attrs' => $this->templater()->formatAttributes($itemOptions),
            'content' => $link,
            'children' => $children
        ]);
    }
}
