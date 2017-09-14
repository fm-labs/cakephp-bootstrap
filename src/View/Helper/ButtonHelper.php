<?php
namespace Bootstrap\View\Helper;

use Cake\View\StringTemplateTrait;

class ButtonHelper extends BaseHelper
{
    use StringTemplateTrait;

    protected $_defaultConfig = [
        'templates' => [
            'button' => '<button class="{{class}}" {{attrs}}>{{label}}</button>',
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
            'buttonDropdownItem' => '<li>{{link}}</li>'
        ]
    ];

    public $helpers = ['Html', 'Bootstrap.Icon'];

    /**
     * @param $title
     * @param array $options
     * @return string
     */
    public function create($label, array $options = [])
    {
        $options += [
            'class' => null, 'icon' => null, 'type' => null, 'size' => null, 'url' => null,
            'split' => null, 'dropdown' => null
        ];

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
                    'link' => $this->Html->link($title, $url, (array) $attrs)
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
                    'attrs' => $btnAttrs
                ]);
                return $this->group($btn . $btndd);
            }

            $btn = $this->templater()->format('buttonDropdown', [
                'class' => $options['class'],
                'icon' => $iconHtml,
                'label' => $label,
                'dropdown' => $ddHtml,
                'attrs' => $btnAttrs
            ]);
            return $this->group($btn);
        }

        $html = $this->templater()->format('button', [
            'class' => $options['class'],
            'icon' => $iconHtml,
            'label' => $label,
            'attrs' => $btnAttrs
        ]);

        return $html;
    }

    public function group($content, array $options = [])
    {
        $options += ['class' => null];

        $html = $this->templater()->format('buttonGroup', [
            'class' => $options['class'],
            'content' => $content,
            'attrs' => $this->templater()->formatAttributes($options, ['class'])
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
        $options = $this->Html->addClass($options, 'btn');

        if (isset($options['icon'])) {
            $title = $this->Icon->create($options['icon']) . " " . $title;

            $options['escape'] = false;
            unset($options['icon']);
        }

        return $this->Html->link($title, $url, $options);
    }

}
