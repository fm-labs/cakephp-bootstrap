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
            'attrs' => $this->templater()->formatAttributes($options, ['toggle', 'class', 'label'])
        ]);

        return $label;
    }
}
