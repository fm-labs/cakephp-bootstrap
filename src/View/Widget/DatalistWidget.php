<?php
namespace Bootstrap\View\Widget;

use Cake\View\Form\ContextInterface;

class DatalistWidget extends BasicWidget
{

    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'name' => null,
            'options' => [],
            'escape' => true,
            'templateVars' => []
        ];

        $this->_templates->add([
            'datalist' => '<datalist id="{{id}}">{{options}}</datalist>',
            'datalistOption' => '<option{{attrs}}>'
        ]);

        $datalistId = uniqid('datalist');
        $datalist = $this->_templates->format('datalist', [
            //'templateVars' => $data['templateVars'],
            'id' => $datalistId,
            'options' => $this->_renderOptions($data['options'])
        ]);
        unset($data['options']);

        $data['type'] = 'text';
        $data['list'] = $datalistId;
        $input = parent::render($data, $context);

        return $input . $datalist;
    }

    protected function _renderOptions($options)
    {
        $optionsHtml = "";
        foreach ($options as $option) {
            $attrs = [
                'value' => $option
            ];

            $optionsHtml .= $this->_templates->format('datalistOption', [
                'attrs' => $this->_templates->formatAttributes($attrs),
            ]);
        }
        return $optionsHtml;
    }
}