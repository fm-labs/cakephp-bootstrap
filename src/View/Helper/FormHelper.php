<?php

namespace Bootstrap\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;
use Cake\View\View;

/**
 * Class FormHelper
 *
 * @package Bootstrap\View\Helper
 */
class FormHelper extends CakeFormHelper
{
    /**
     * @var bool
     */
    static public $useHorizontal = false;

    /**
     * @var bool
     */
    protected $_horizontal;

    /**
     * @var array
     */
    protected $_templateOriginals = [];

    /**
     * @param View $View
     * @param array $config
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $this->templater()->load('Bootstrap.form_templates');
        //$this->templater()->load('Bootstrap.form_horizontal_templates');

        $widgets = [
            '_default' => ['Bootstrap\View\Widget\BasicWidget', 'datalist'],
            'button' => ['Bootstrap\View\Widget\ButtonWidget'],
            'hidden' => ['Bootstrap\View\Widget\HiddenWidget'],
            'textarea' => ['Bootstrap\View\Widget\TextareaWidget'],
            'datalist' => ['Bootstrap\View\Widget\DatalistWidget'],
        ];
        foreach ($widgets as $type => $config) {
            $this->addWidget($type, $config);
        }
    }

    /**
     * @param null $model
     * @param array $options
     * @return string
     */
    public function create($model = null, array $options = [])
    {
        $this->_horizontal = self::$useHorizontal;
        if (isset($options['horizontal'])) {
            $this->_horizontal = $options['horizontal'];
            unset($options['horizontal']);
        }

        if ($this->_horizontal === true) {
            $options = $this->addClass($options, 'form-horizontal');
            $options['templates'] = 'Bootstrap.form_horizontal_templates';
        }

        return parent::create($model, $options);
    }

    /**
     * @param array $secureAttributes
     * @return string
     */
    public function end(array $secureAttributes = [])
    {
        $this->_horizontal = self::$useHorizontal;

        return parent::end($secureAttributes);
    }

    /**
     * @param string $fieldName
     * @param null $text
     * @param array $options
     * @return string
     */
    public function label($fieldName, $text = null, array $options = [])
    {
        if ($this->_horizontal && !isset($options['input'])) {
        //    $options = $this->addClass($options, 'col-sm-3');
            $options = $this->addClass($options, 'control-label');
        }

        return parent::label($fieldName, $text, $options);
    }

    /**
     * @param string $fieldName
     * @param array $options
     * @return string
     */
    public function input($fieldName, array $options = [])
    {
        if ($this->_horizontal) {
        }

        return parent::input($fieldName, $options);
    }

    /**
     * @param $template
     */
    protected function _swapTemplate($template)
    {
        $original = $this->templater()->get($template);
        $horizontal = $this->templater()->get('horizontal_' . $template);

        if ($horizontal) {
            $this->templater()->add([$template => $horizontal]);
            $this->_templateOriginals[$template] = $original;
        }
    }

    /**
     * @param $template
     */
    protected function _restoreTemplate($template)
    {
        if (isset($this->_templateOriginals[$template])) {
            $this->templater()->add([$template => $this->_templateOriginals[$template]]);
            unset($this->_templateOriginals[$template]);
        }
    }

    /**
     *
     */
    protected function _restoreAllTemplates()
    {
        $this->templater()->add($this->_templateOriginals);
        $this->_templateOriginals = [];
    }
}
