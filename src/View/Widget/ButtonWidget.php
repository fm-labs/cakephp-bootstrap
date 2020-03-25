<?php
declare(strict_types=1);

namespace Bootstrap\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\ButtonWidget as CakeButtonWidget;

/**
 * Class ButtonWidget
 *
 * @package Bootstrap\View\Widget
 */
class ButtonWidget extends CakeButtonWidget
{
    /**
     * @param array $data
     * @param \Cake\View\Form\ContextInterface $context
     * @return string
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'type' => null,
        ];

        if (!isset($data['class'])) {
            $data['class'] = '';
        }

        if ($data['type'] == 'submit') {
            $data['class'] .= ' primary';
        }

        $classParts = explode(' ', trim($data['class']));
        $class = array_map(function ($val) {
            if (in_array($val, ['default', 'success', 'danger', 'info', 'warning', 'link', 'primary'])) {
                return 'btn-' . $val;
            }

            return $val;
        }, $classParts);

        array_unshift($class, 'btn');
        $class = array_unique($class);
        $data['class'] = trim(join(' ', $class));

        return parent::render($data, $context);
    }
}
