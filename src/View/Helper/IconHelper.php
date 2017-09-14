<?php
namespace Bootstrap\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

class IconHelper extends Helper
{
    use StringTemplateTrait;

    protected $_defaultConfig = [
        'templates' => [
            // 'icon' => '<span class="glyphicon glyphicon-{{class}}"{{attrs}}></span>', #bootstrap style
            'icon' => '<i class="fa fa-{{class}}"{{attrs}}></i>', # fontawesome style
        ]
    ];

    public $helpers = ['Html'];

    /**
     * @param $class
     * @param array $options
     * @return null|string
     */
    public function create($class, $options = [])
    {
        $options += ['tag' => 'icon', 'class' => '', 'attrs' => []];

        $tag = $options['tag'];
        unset($options['tag']);

        if (isset($options['class'])) {
            $options['class'] = sprintf("%s %s", $class, $options['class']);
        }

        return $this->templater()->format($tag, $options);
    }
}
