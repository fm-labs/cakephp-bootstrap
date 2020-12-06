<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Class IconHelper
 *
 * @package Bootstrap\View\Helper
 * @method glyphicon(string $icon, array $options = []): string
 * @method fontawesome(string $icon, array $options = []): string
 */
class IconHelper extends Helper
{
    use StringTemplateTrait;

    protected $_defaultConfig = [
        'defaultTemplate' => 'fontawesome',
        'templates' => [
            'icon_glyphicon' => '<span class="glyphicon glyphicon-{{class}}"{{attrs}}></span>', #bootstrap style
            'icon_fontawesome' => '<i class="fa fa-{{class}}"{{attrs}}></i>', # fontawesome style
        ],
    ];

    public $helpers = ['Html'];

    /**
     * @param string $icon Icon name
     * @param array $options Icon options
     * @return null|string
     */
    public function create(string $icon, array $options = []): string
    {
        $options += ['template' => null, 'class' => null, 'attrs' => []];

        $template = $options['template'] ?? $this->getConfig('defaultTemplate');
        $class = trim(sprintf('%s %s', $icon, (string)$options['class']));

        return $this->templater()->format('icon_' . $template, [
            'class' => $class,
            'attrs' => $this->templater()->formatAttributes($options['attrs']),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function __call($method, $params)
    {
        if (isset($this->_config['templates']['icon_' . $method]) && count($params) >= 1) {
            $icon = $params[0];
            $options = $params[1] ?? [];
            $options['template'] = $method;

            return $this->create($icon, $options);
        }

        parent::__call($method, $params);
    }

//    /**
//     * Create fontawesome icon.
//     *
//     * @param string $icon Icon name
//     * @param array $options Icon options
//     * @return string
//     */
//    public function fontawesome(string $icon, array $options = []): string
//    {
//        $options['template'] = 'fontawesome';
//        return $this->create($icon, $options);
//    }
//
//    /**
//     * Create glyphicon icon.
//     *
//     * @param string $icon Icon name
//     * @param array $options Icon options
//     * @return string
//     */
//    public function glyphicon(string $icon, array $options = []): string
//    {
//        $options['template'] = 'glyphicon';
//        return $this->create($icon, $options);
//    }
}
