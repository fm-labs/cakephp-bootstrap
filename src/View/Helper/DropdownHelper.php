<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

/**
 * Dropdown helper
 *
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class DropdownHelper extends Helper
{
    use StringTemplateTrait;

    public $helpers = ['Url'];

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [
        'templates' => [
            'ddMenu' => '<ul class="{{class}}">{{items}}</ul>',
            'ddMenuItem' => ' <li><a class="{{class}}" href="{{url}}" {{attrs}}>{{title}}</a></li>',
            'ddButtonContainer' => '<div class="dropdown">{{button}}{{menu}}</div>',
            'ddButton' => '<button class="{{class}}" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{title}}</button>',
            'ddSplitButton' => '',
        ]
    ];

    public function menu(array $items)
    {
        return $this->templater()->format('ddMenu', [
            'class' => 'dropdown-menu',
            'items' => $this->_renderMenuItems($items),
        ]);
    }

    public function _renderMenuItems(array $items)
    {
        $itemsHtml = "";
        foreach ($items as $item) {
            $attrs = $item['attrs'] ?? $item['attr']; // @TODO Fix attrs/attr bug

            $itemsHtml .= $this->templater()->format('ddMenuItem', [
                'class' => 'dropdown-item',
                'title' => $item['title'],
                'url' => $this->Url->build($item['url']),
                'attrs' => $this->templater()->formatAttributes($attrs, ['class', 'url']),
            ]);
        }
        return $itemsHtml;
    }


    public function button(string $title, array $menuItems = [], array $options = [])
    {
        $menuHtml = $this->menu($menuItems);

        $defaultOptions = [
            'class' => 'btn btn-sm btn-outline-secondary dropdown',
            'type' => 'button',
            'data-bs-toggle' => 'dropdown',
            'aria-expanded' => 'false',
        ];
        $options = array_merge($defaultOptions, $options);
        $button = $this->templater()->format('ddButton', [
            'class' => $options['class'],
            'title' => $title,
            'attrs' => $this->templater()->formatAttributes($options, ['class']),
        ]);

        return $this->templater()->format('ddButtonContainer', [
            'class' => 'dropdown',
            'button' => $button,
            'menu' => $menuHtml,
        ]);
    }

}
