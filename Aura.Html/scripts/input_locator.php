<?php
namespace Aura\Html\Helper\Input;

use Aura\Html\Locator;

return new Locator([
    'button'            => function () { return new Button; },
    'checkbox'          => function () { return new Checkbox; },
    'color'             => function () { return new Value; },
    'date'              => function () { return new Value; },
    'datetime'          => function () { return new Value; },
    'datetime-local'    => function () { return new Value; },
    'email'             => function () { return new Value; },
    'file'              => function () { return new Button; },
    'hidden'            => function () { return new Value; },
    'image'             => function () { return new Value; },
    'month'             => function () { return new Value; },
    'number'            => function () { return new Value; },
    'password'          => function () { return new Value; },
    'radio'             => function () { return new Radio; },
    'radios'            => function () { return new Radios(new Radio); },
    'range'             => function () { return new Value; },
    'reset'             => function () { return new Value; },
    'search'            => function () { return new Value; },
    'select'            => function () { return new Select; },
    'submit'            => function () { return new Button; },
    'tel'               => function () { return new Value; },
    'text'              => function () { return new Value; },
    'textarea'          => function () { return new Textarea; },
    'time'              => function () { return new Value; },
    'url'               => function () { return new Value; },
    'week'              => function () { return new Value; },
]);
