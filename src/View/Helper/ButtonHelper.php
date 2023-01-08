<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\StringTemplateTrait;

class ButtonHelper extends BaseComponentHelper
{
    use StringTemplateTrait;

    protected $_defaultConfig = [
        'templates' => [
            'button' => '<button class="{{class}}"{{attrs}}>{{label}}</button>',
            //'buttonLink' => '<a class="btn btn-{{class}}"{{attrs}}>{{label}}</a>',
            'buttonGroup' => '<div class="btn-group {{class}}" {{attrs}}>{{content}}</div>',
            'buttonDropdown' => '
                <button class="{{class}} dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    {{label}}
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                     {{dropdown}}
                </ul>
            ',
            'buttonDropdownItem' => '<li>{{link}}</li>',
        ],
    ];

    public $helpers = ['Html', 'Bootstrap.Icon'];

    /**
     * @param $title
     * @param array $options
     * @return string
     */
    public function create($label, array $options = [])
    {
        $defaultOptions = [
            'class' => null, 'icon' => null, 'type' => null, 'size' => null, 'url' => null,
            'split' => null, 'dropdown' => null,
        ];

        $options += $defaultOptions;
        $options = $this->Html->addClass($options, 'btn');
        $options = $this->Html->addClass($options, $this->_mapTypeClass($options['type'], 'btn'));
        $options = $this->Html->addClass($options, $this->_mapSizeClass($options['size'], 'btn'));

        $iconHtml = "";
        if (isset($options['icon'])) {
            $iconHtml = $this->Icon->create($options['icon']);
        }

        $btnAttrs = $this->templater()->formatAttributes($options, ['class', 'icon', 'size', 'type', 'url', 'dropdown', 'split']);

        if (is_array($options['dropdown'])) {
            $ddHtml = "";
            foreach ($options['dropdown'] as $ddItem) {
                $title = $url = $attrs = null;
                extract($ddItem, EXTR_IF_EXISTS);
                $ddHtml .= $this->templater()->format('buttonDropdownItem', [
                    'link' => $this->Html->link($title, $url, (array)$attrs),
                ]);
            }

            if ($options['split'] == true) {
                $_options = $options;
                unset($_options['dropdown']);
                unset($_options['split']);

                $btn = $this->create($label, $_options);
                $btndd = $this->templater()->format('buttonDropdown', [
                    'class' => $options['class'],
                    'icon' => $iconHtml,
                    'label' => "",
                    'dropdown' => $ddHtml,
                    'attrs' => $btnAttrs,
                ]);

                return $this->group($btn . $btndd);
            }

            $btn = $this->templater()->format('buttonDropdown', [
                'class' => $options['class'],
                'icon' => $iconHtml,
                'label' => $label,
                'dropdown' => $ddHtml,
                'attrs' => $btnAttrs,
            ]);

            return $this->group($btn);
        }

        if ($options['url']) {
            $url = $options['url'];
            unset($options['url']);
            $btnAttrs = array_diff_key($options, $defaultOptions);
            $btnAttrs['class'] = $options['class'];

            if ($iconHtml) {
                $label = $iconHtml . "&nbsp" . $label;
                $btnAttrs['escape'] = false;
            }

            return $this->Html->link($label, $url, $btnAttrs);
        }

        $html = $this->templater()->format('button', [
            'class' => $options['class'],
            'icon' => $iconHtml,
            'label' => $label,
            'attrs' => $btnAttrs,
        ]);

        return $html;
    }

    public function group($content, array $options = [])
    {
        $options += ['class' => null];

        $html = $this->templater()->format('buttonGroup', [
            'class' => $options['class'],
            'content' => $content,
            'attrs' => $this->templater()->formatAttributes($options, ['class']),
        ]);

        return $html;
    }

    /**
     * @param $title
     * @param $url
     * @param array $options
     * @return string
     */
    public function link($title, $url, array $options = [])
    {
        $options += ['url' => $url];

        return $this->create($title, $options);
    }

    public function primary($label, array $options)
    {
        $options += ['type' => 'primary'];

        return $this->create($label, $options);
    }

    public function info($label, array $options)
    {
        $options += ['type' => 'info'];

        return $this->create($label, $options);
    }

    public function warning($label, array $options)
    {
        $options += ['type' => 'warning'];

        return $this->create($label, $options);
    }

    public function danger($label, array $options)
    {
        $options += ['type' => 'danger'];

        return $this->create($label, $options);
    }
}
