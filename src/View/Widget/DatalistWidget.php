<?php
declare(strict_types=1);

namespace Bootstrap\View\Widget;

use Cake\View\Form\ContextInterface;

/**
 * Class DatalistWidget
 *
 * @package Bootstrap\View\Widget
 */
class DatalistWidget extends BasicWidget
{
    /**
     * @param array $data
     * @param \Cake\View\Form\ContextInterface $context
     * @return string
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'name' => null,
            'options' => [],
            'escape' => true,
            'templateVars' => [],
        ];

        $this->_templates->add([
            'datalist' => '<datalist id="{{id}}">{{options}}</datalist>',
            'datalistOption' => '<option{{attrs}}>',
        ]);

        $datalistId = uniqid('datalist');
        $datalist = $this->_templates->format('datalist', [
            //'templateVars' => $data['templateVars'],
            'id' => $datalistId,
            'options' => $this->_renderOptions($data['options']),
        ]);
        unset($data['options']);

        $data['type'] = 'text';
        $data['list'] = $datalistId;
        $input = parent::render($data, $context);

        return $input . $datalist;
    }

    /**
     * @param $options
     * @return string
     */
    protected function _renderOptions($options)
    {
        $optionsHtml = "";
        foreach ($options as $option) {
            $attrs = [
                'value' => $option,
            ];

            $optionsHtml .= $this->_templates->format('datalistOption', [
                'attrs' => $this->_templates->formatAttributes($attrs),
            ]);
        }

        return $optionsHtml;
    }
}
