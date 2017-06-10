<?php
namespace Bootstrap\View\Helper;

use Cake\Datasource\EntityTrait;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Class UiHelper
 *
 * @package Backend\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\FormHelper $Form
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class UiHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * @var array
     */
    public $helpers = ['Html', 'Url', 'Bootstrap.Form', ];

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            // 'icon' => '<span class="glyphicon glyphicon-{{class}}"{{attrs}}></span>', #bootstrap style
            'icon' => '<i class="fa fa-{{class}}"{{attrs}}></i>', # fontawesome style
            'modal' => '<div class="modal"></div>',
            'label' => '<span class="label label-{{class}}"{{attrs}}>{{label}}</span>',
            'menu' => '<ul{{attrs}}>{{items}}</ul>',
            'menuItem' => '<li{{attrs}}>{{content}}</li>',
            'menuItemDropdown' => '<li class="dropdown"{{attrs}}>{{content}}{{children}}</li>',
            'menuDropdownButton' => '<a{{attrs}}>{{title}} <span class="caret"></span></a>',
            'menuLink' => '<a{{attrs}}>{{title}}</a>',
            //'button' => '<button{{attrs}}>{{content}}</button>'
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
        $options = $this->Html->addClass($options, 'btn');
        //if ($url) {
            return $this->link($title, $url, $options);
        //}
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
        $options += ['label' => null, 'class' => null, 'toggle' => null];
        $label = $toggle = $class = null;
        #$map = [];
        extract($options, EXTR_IF_EXISTS);

        if (empty($map)) {
            $map = [
                0 => [__('No'), 'danger'],
                1 => [__('Yes'), 'success']
            ];
        }

        if (!$label) {
            $label = (string)$status;
        }

        if (!$class) {
            $class = 'default';
        }

        if (!is_string($status)) {
            $status = (int)$status;
        }

        if (array_key_exists($status, $map)) {
            $stat = $map[$status];
            if (is_string($stat)) {
                $stat = [$status, $stat];
            }

            if (is_array($stat) && count($stat) == 2) {
                list($label, $class) = $stat;
            }
        }

        $label = $this->templater()->format('label', [
            'class' => $class,
            'label' => $label,
            'attrs' => $this->templater()->formatAttributes($options, ['toggle', 'class', 'label', 'map'])
        ]);

        return $label;
    }

    /**
     * @param $class
     * @param array $options
     * @return null|string
     */
    public function icon($class, $options = [])
    {
        $options += ['tag' => 'icon', 'class' => '', 'attrs' => []];

        $tag = $options['tag'];
        unset($options['tag']);

        if (isset($options['class'])) {
            $options['class'] = sprintf("%s %s", $class, $options['class']);
        }

        return $this->templater()->format($tag, $options);
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
            if (is_object($item) && $item instanceof EntityTrait) {
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
