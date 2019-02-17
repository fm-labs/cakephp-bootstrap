<?php
namespace Bootstrap\View\Helper;

use Cake\View\StringTemplateTrait;

class LabelHelper extends BaseHelper
{
    use StringTemplateTrait;

    protected $_defaultConfig = [
        'templates' => [
            'label' => '<span class="label label-{{class}}"{{attrs}}>{{label}}</span>',
        ]
    ];

    public function create($label, $options = [])
    {
        $options += ['class' => null];

        $label = $this->templater()->format('label', [
            'class' => $options['class'],
            'label' => $label,
            'attrs' => $this->templater()->formatAttributes($options, ['class'])
        ]);

        return $label;
    }

    /**
     * @param $status
     * @param array $options
     * @param array $map
     * @return null|string
     */
    public function status($status, $options = [], $map = [])
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

        if (!$class) {
            $class = 'default';
        }

        if (is_bool($status)) {
            $status = (int)$status;
        }

        if (array_key_exists($status, $map)) {
            $mapped = $map[$status];
            if (is_string($mapped)) {
                $label = $mapped;
            } elseif (is_array($mapped) && count($mapped) == 2) {
                list($label, $class) = $mapped;
            }
        }

        if (!$label) {
            $label = (string)$status;
        }

        $out = $this->templater()->format('label', [
            'class' => $class,
            'label' => $label,
            'attrs' => $this->templater()->formatAttributes($options, ['toggle', 'class', 'label'])
        ]);

        return $out;
    }

    public function success($label, $options = [])
    {
        $options['class'] = __FUNCTION__;

        return $this->create($label, $options);
    }

    public function danger($label, $options = [])
    {
        $options['class'] = __FUNCTION__;

        return $this->create($label, $options);
    }

    public function info($label, $options = [])
    {
        $options['class'] = __FUNCTION__;

        return $this->create($label, $options);
    }

    public function warning($label, $options = [])
    {
        $options['class'] = __FUNCTION__;

        return $this->create($label, $options);
    }
}
