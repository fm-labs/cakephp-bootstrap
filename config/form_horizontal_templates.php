<?php
return [
    //'input' => 'input type="{{type}}" name="{{name}}"{{attrs}}/>',
    //'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',

    'formGroup' => '<div class="col-sm-3 col-md-4">{{label}}</div><div class="col-sm-9 col-md-8">{{input}}</div>',

    'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
    'checkboxFormGroup' => '{{label}}',
    'checkboxWrapper' => '<div class="checkbox-wrapper">{{label}}</div>',
    'nestingLabel' => '<div class="col-sm-3 col-md-4">{{hidden}}<label{{attrs}}>{{text}}</label></div><div class="col-sm-9 col-md-8">{{input}}</div>',

    'radioContainer' => '<div class="form-group input-radio">{{content}}',
    'radioFormGroup' => '{{label}}<div class="col-sm-9 col-md-8">{{input}}</div>',
    'radioWrapper' => '<div class="radio">{{label}}</div>',

    'multicheckboxContainer' => '<div class="form-group {{required}}">{{content}}</div>',
    'multicheckboxFormGroup' => '{{label}}<div class="multicheckbox-formgroup col-sm-9 col-md-8">{{input}}</div>'
];
