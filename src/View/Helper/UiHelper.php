<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cupcake\Menu\MenuItem;
use Cake\Datasource\EntityTrait;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Class UiHelper
 *
 * @package Admin\View\Helper
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
        ],
    ];

    /**
     * @param string $title Title
     * @param string|array $url URL
     * @param array $options Additional options
     * @return string
     */
    public function button($title, $url, array $options = [])
    {
        return $this->Button->link($title, $url, $options);
    }

    /**
     * @param string $title Title
     * @param string|array $url URL
     * @param array $options Additional options
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
     * @param string $title Title
     * @param string|array $url URL
     * @param array $options Additional options
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
     * @param string $title Title
     * @param string|array $url URL
     * @param array $options Additional options
     * @return string
     */
    public function deleteLink($title, $url = null, array $options = [])
    {
        return $this->postLink($title, $url, $options);
    }

    /**
     * @param string|int $status Status value
     * @param array $options Additional options
     * @param array $map Status map
     * @return null|string
     */
    public function statusLabel($status, $options = [], $map = [])
    {
        deprecationWarning("UiHelper::statusLabel is deprecated. Use StatusHelper instead.");
        return $this->Label->status($status, $options, $map);
    }

    /**
     * @param string $class Icon class
     * @param array $options Additional options
     * @return null|string
     */
    public function icon($class, $options = [])
    {
        return $this->Icon->create($class, $options);
    }

    /**
     * @param array $menuList List of menu items
     * @param array $menuOptions Menu options
     * @param array $childMenuOptions Child menu options
     * @param array $itemOptions Item options
     * @return null|string
     */
    public function menu($menuList = [], $menuOptions = [], $childMenuOptions = [], $itemOptions = [])
    {
        $menuOptions += [
            'class' => null,
            'itemscope' => 'itemscope',
            'itemtype' => 'http://www.schema.org/SiteNavigationElement',
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
     * @param array $item Menu item
     * @param array $childMenuOptions Child menu options
     * @param array $itemOptions Item options
     * @return null|string
     */
    public function menuItem(array $item = [], array $childMenuOptions = [], array $itemOptions = [])
    {
        $item += ['url' => null, 'children' => [], 'title' => null, 'class' => null, 'hide_in_nav' => null];
        $item['class'] = $item['class'] ?? 'nav-link';

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
        $children = $item['children'] ?? [];
        unset($item['children']);

        // legacy support
        // _children is now children
        if (empty($children) && isset($item['_children'])) {
            $children = $item['_children'];
            unset($item['_children']);
        }

        $item['title'] = $item['title'] ?: $this->Url->build($url);
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
                //'href' => '#',
                //'data-href' => $url ? $this->Url->build($url) : null,
                'href' => $url ? $this->Url->build($url) : '#',
            ];
            $ddAttrs += $item;
            $ddLink = $this->templater()->format('menuDropdownButton', [
                'attrs' => $this->templater()->formatAttributes($ddAttrs, ['requireRoot', 'data-icon']),
                'title' => $item['title'],
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
            'children' => $children,
        ]);
    }
}
