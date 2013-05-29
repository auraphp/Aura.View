<?php
namespace Aura\Html\Helper\Form;

use Aura\Html\Locator;

return new Locator([
    'button'            => function () { return new Button; },
    'checkbox'          => function () { return new Checkbox; },
    'color'             => function () { return new Input; },
    'date'              => function () { return new Input; },
    'datetime'          => function () { return new Input; },
    'datetime-local'    => function () { return new Input; },
    'email'             => function () { return new Input; },
    'file'              => function () { return new Button; },
    'hidden'            => function () { return new Input; },
    'image'             => function () { return new Input; },
    'month'             => function () { return new Input; },
    'number'            => function () { return new Input; },
    'password'          => function () { return new Input; },
    'radio'             => function () { return new Radio; },
    'radios'            => function () { return new Radios(new Radio); },
    'range'             => function () { return new Input; },
    'reset'             => function () { return new Input; },
    'search'            => function () { return new Input; },
    'select'            => function () { return new Select; },
    'submit'            => function () { return new Button; },
    'tel'               => function () { return new Input; },
    'text'              => function () { return new Input; },
    'textarea'          => function () { return new Textarea; },
    'time'              => function () { return new Input; },
    'url'               => function () { return new Input; },
    'week'              => function () { return new Input; },
]);
