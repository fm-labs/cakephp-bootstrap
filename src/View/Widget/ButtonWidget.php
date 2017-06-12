<?php
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
     * @param ContextInterface $context
     * @return string
     */
    public function render(array $data, ContextInterface $context)
    {
        if (!isset($data['class'])) {
            $data['class'] = '';
        }

        $class = array_map(function ($val) {
            if (in_array($val, ['default', 'success', 'danger', 'info', 'warning', 'link'])) {
                return 'btn-'.$val;
            }

            return $val;
        }, explode(' ', $data['class']));

        $class = array_merge(['btn'], $class);
        $class = array_unique($class);
        $data['class'] = trim(join(' ', $class));

        return parent::render($data, $context);
    }
}
