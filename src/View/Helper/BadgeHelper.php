<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\StringTemplateTrait;

class BadgeHelper extends BaseComponentHelper
{
    use StringTemplateTrait;

    protected $_defaultConfig = [
        'templates' => [
            'badge' => '<span class="badge text-bg-{{class}}"{{attrs}}>{{label}}</span>',
        ],
    ];

    public function create($label, $options = [])
    {
        $options += ['class' => null];

        $label = $this->templater()->format('badge', [
            'class' => $options['class'],
            'label' => $label,
            'attrs' => $this->templater()->formatAttributes($options, ['class']),
        ]);

        return $label;
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
