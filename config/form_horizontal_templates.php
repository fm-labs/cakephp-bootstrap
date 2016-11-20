<?php
return [
    'input' => '<div class="col-sm-9"><input type="{{type}}" name="{{name}}"{{attrs}}/></div>',

    'select' => '<div class="col-sm-9"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',

    'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
    'checkboxFormGroup' => '<div class="col-sm-offset-3 col-sm-9"><div class="checkbox">{{label}}</div></div>',
    'checkboxWrapper' => '<div class="checkbox-wrapper">{{label}}</div>',

    'radioContainer' => '<div class="form-group input-radio">{{content}}',
    'radioFormGroup' => '{{label}}<div class="col-sm-9">{{input}}</div>',
    'radioWrapper' => '<div class="radio">{{label}}</div>',

    'multicheckboxContainer' => '<div class="form-group {{required}}">{{content}}</div>',
    'multicheckboxFormGroup' => '{{label}}<div class="multicheckbox-formgroup col-sm-9">{{input}}</div>'
];