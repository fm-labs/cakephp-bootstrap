<?php
namespace Bootstrap\View\Widget;

use Cake\View\Widget\ButtonWidget as CakeButtonWidget;
use Cake\View\Form\ContextInterface;

class ButtonWidget extends CakeButtonWidget
{

    public function render(array $data, ContextInterface $context)
    {
        if (!isset($data['class'])) {
            $data['class'] = '';
        }

        $class = explode(' ', $data['class']);
        $class = array_merge(['btn'], $class);
        $class = array_unique($class);
        $data['class'] = join(' ', $class);

        return parent::render($data, $context);
    }

}
