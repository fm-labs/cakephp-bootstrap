<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

/**
 * Navbar helper
 *
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class NavHelper extends Helper
{
    use StringTemplateTrait;

    public array $helpers = ['Url'];

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'templates' => [
            'nav' => '<ul class="{{class}}">{{items}}</ul>',
            'navItem' => ' <li class="{{class}}">{{link}}</li>',
            'navLink' => ' <a class="nav-link"{{attrs}}>{{title}}</a>'
        ]
    ];

    public function create(array $items, array $options = [])
    {
        $options += ['class' => null];

        return $this->templater()->format('nav', [
            'class' => $options['class'],
            'attrs' => $this->templater()->formatAttributes($options, ['class']),
            'items' => $this->_renderNavItems($items),
        ]);
    }

    public function _renderNavItems(array $items)
    {
        $itemsHtml = "";
        foreach ($items as $item) {
            $attrs = $item['attr'] ?? $item['attrs'] ?? []; // @TODO Fix attr/attrs bug
            $itemsHtml .= $this->templater()->format('navItem', [
                'class' => 'nav-item',
                'link' => $this->_renderNavLink($item['title'], $item['url'], $attrs),
            ]);
        }
        return $itemsHtml;
    }


    public function _renderNavLink(string $title, $url, array $options = [])
    {
        $options['href'] = $this->Url->build($url);
        return $this->templater()->format('navLink', [
            'class' => 'nav-link',
            'title' => $title,
            'attrs' => $this->templater()->formatAttributes($options, ['class']),
        ]);
    }

}
