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
     * Default config for the helper.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'idPrefix' => null,
        'errorClass' => 'form-error',
        'typeMap' => [
            'string' => 'text', 'datetime' => 'datetime', 'boolean' => 'checkbox',
            'timestamp' => 'datetime', 'text' => 'textarea', 'time' => 'time',
            'date' => 'date', 'float' => 'number', 'integer' => 'number',
            'decimal' => 'number', 'binary' => 'file', 'uuid' => 'string'
        ],
        'templates' => [
            'button' => '<button{{attrs}}>{{text}}</button>',
            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
            'checkboxFormGroup' => '{{label}}',
            'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
            'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
            //'error' => '<div class="error-message">{{content}}</div>',
            'error' => '<span class="help-block">{{content}}</span>',
            'errorList' => '<ul>{{content}}</ul>',
            'errorItem' => '<li>{{text}}</li>',
            'file' => '<input type="file" name="{{name}}"{{attrs}}>',
            'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
            'formStart' => '<form{{attrs}}>',
            'formEnd' => '</form>',
            'formGroup' => '{{label}}{{input}}',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
            'inputSubmit' => '<input type="{{type}}"{{attrs}}/>',
            //'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}</div>',
            'inputContainer' => '<div class="form-group {{required}}">{{content}}</div>',
            //'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
            'inputContainerError' => '<div class="form-group has-error {{required}}">{{content}}{{error}}</div>',
            'label' => '<label{{attrs}}>{{text}}</label>',
            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
            'legend' => '<legend>{{text}}</legend>',
            'multicheckboxTitle' => '<legend>{{text}}</legend>',
            'multicheckboxWrapper' => '<fieldset{{attrs}}>{{content}}</fieldset>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
            'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
            'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'radioWrapper' => '{{label}}',
            'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
            'submitContainer' => '<div class="submit">{{content}}</div>',

            // Custom
            'inputHidden' => '<input type="hidden" name="{{name}}"{{attrs}} />',


// Original templates as of CakePHP 3.3
//            'button' => '<button{{attrs}}>{{text}}</button>',
//            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
//            'checkboxFormGroup' => '{{label}}',
//            'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
//            'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
//            'error' => '<div class="error-message">{{content}}</div>',
//            'errorList' => '<ul>{{content}}</ul>',
//            'errorItem' => '<li>{{text}}</li>',
//            'file' => '<input type="file" name="{{name}}"{{attrs}}>',
//            'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
//            'formStart' => '<form{{attrs}}>',
//            'formEnd' => '</form>',
//            'formGroup' => '{{label}}{{input}}',
//            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
//            'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
//            'inputSubmit' => '<input type="{{type}}"{{attrs}}/>',
//            'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}</div>',
//            'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
//            'label' => '<label{{attrs}}>{{text}}</label>',
//            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
//            'legend' => '<legend>{{text}}</legend>',
//            'multicheckboxTitle' => '<legend>{{text}}</legend>',
//            'multicheckboxWrapper' => '<fieldset{{attrs}}>{{content}}</fieldset>',
//            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
//            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
//            'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
//            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
//            'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
//            'radioWrapper' => '{{label}}',
//            'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
//            'submitContainer' => '<div class="submit">{{content}}</div>',
        ],

        'templatesHorizontal' => [
            //'input' => 'input type="{{type}}" name="{{name}}"{{attrs}}/>',
            //'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',

            'formGroup' => '<div class="col-sm-3 col-md-3 col-lg-3">{{label}}</div><div class="col-sm-9 col-md-9 col-lg-9">{{input}}</div>',

            'submitContainer' => '<div class="form-group"><div class="col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-sm-9 col-md-9 col-lg-9"><div class="submit">{{content}}</div></div></div>',

            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
            'checkboxFormGroup' => '{{label}}',
            'checkboxWrapper' => '<div class="checkbox-wrapper">{{label}}</div>',
            'nestingLabel' => '<div class="col-sm-3 col-md-3 col-lg-3">{{hidden}}<label{{attrs}}>{{text}}</label></div><div class="col-sm-9 col-md-9 col-lg-9">{{input}}</div>',

            'radioContainer' => '<div class="form-group input-radio">{{content}}',
            'radioFormGroup' => '{{label}}<div class="col-sm-9 col-md-9 col-lg-9">{{input}}</div>',
            'radioWrapper' => '<div class="radio">{{label}}</div>',

            'multicheckboxContainer' => '<div class="form-group {{required}}">{{content}}</div>',
            'multicheckboxFormGroup' => '{{label}}<div class="multicheckbox-formgroup col-sm-9 col-md-9 col-lg-9">{{input}}</div>'
        ]
    ];

    /**
     * Default widgets
     *
     * @var array
     */
    protected $_defaultWidgets = [
        // bootstrap cake-default overrides
        'button' => ['Bootstrap\View\Widget\ButtonWidget'],
        'textarea' => ['Bootstrap\View\Widget\TextareaWidget'],
        '_default' => ['Bootstrap\View\Widget\BasicWidget', 'datalist'],

        // bootstrap widgets
        'datalist' => ['Bootstrap\View\Widget\DatalistWidget'],
        'hidden' => ['Bootstrap\View\Widget\HiddenWidget'],

        // cake-default
        'checkbox' => ['Checkbox'],
        'file' => ['File'],
        'label' => ['Label'],
        'nestingLabel' => ['NestingLabel'],
        'multicheckbox' => ['MultiCheckbox', 'nestingLabel'],
        'radio' => ['Radio', 'nestingLabel'],
        'select' => ['SelectBox'],
        'datetime' => ['DateTime', 'select'],
        //'textarea' => ['Textarea'],
        //'button' => ['Button'],
        //'_default' => ['Basic'],

    ];


    /**
     * @param View $View
     * @param array $config
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);
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
            $options['templates'] = $this->config('templatesHorizontal');
        }

        return parent::create($model, $options);
    }


//    /**
//     * Load custom override templates
//     */
//    protected function _loadCustomTemplates()
//    {
//        $templates = $this->config('templatesCustom');
//        if (!$templates) {
//            return;
//        }
//
//        if (is_string($templates)) {
//            $this->templater()->load($templates);
//        } else {
//            $this->templater()->add($templates);
//        }
//    }
//
//    /**
//     * Load custom override templates
//     */
//    protected function _loadHorizontalTemplates()
//    {
//        $templates = $this->config('templatesHorizontal');
//        if (!$templates) {
//            return;
//        }
//
//        if (is_string($templates)) {
//            $this->templater()->load($templates);
//        } else {
//            $this->templater()->add($templates);
//        }
//    }

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
        if ($this->_horizontal /*&& !isset($options['input'])*/) {
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
     * @deprecated
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
     * @deprecated
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
     * @deprecated
     */
    protected function _restoreAllTemplates()
    {
        $this->templater()->add($this->_templateOriginals);
        $this->_templateOriginals = [];
    }
}
