<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

/**
 * Navbar helper
 */
class NavbarHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [
        'templates' => [
            'nav' => '<ul class="{{class}}">{{items}}</ul>',
            'navItem' => ' <li class="{{class}}">{{link}}</li>',
            'navLink' => ' <a class="nav-link"{{attrs}}>{{title}}</a>'
        ]
    ];

    public function nav(array $items)
    {
        return $this->templater()->format('nav', [
            'class' => 'navbar-nav me-auto mb-2 mb-lg-0',
            'items' => $this->_renderNavItems($items),
        ]);
    }

    public function _renderNavItems(array $items)
    {
        $itemsHtml = "";
        foreach ($items as $item) {
            $itemsHtml .= $this->templater()->format('navItem', [
                'class' => 'nav-item',
                'link' => $this->_renderNavLink($item['title'], $item['url'], $item['attrs']),
            ]);
        }
        return $itemsHtml;
    }


    public function _renderNavLink(string $title, $url, array $options = [])
    {
        return $this->templater()->format('navLink', [
            'class' => 'nav-link',
            'title' => $title,
            'url' => $url,
            'attrs' => $this->templater()->formatAttributes($options),
        ]);
    }

}
