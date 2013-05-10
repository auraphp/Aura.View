<?php
use Aura\View\Helper;

return [
    'button'         => function () { return new Helper\Form\Input\Generic; },
    'checkbox'       => function () { return new Helper\Form\Input\Checked; },
    'color'          => function () { return new Helper\Form\Input\Value; },
    'date'           => function () { return new Helper\Form\Input\Value; },
    'datetime'       => function () { return new Helper\Form\Input\Value; },
    'datetime-local' => function () { return new Helper\Form\Input\Value; },
    'email'          => function () { return new Helper\Form\Input\Value; },
    'file'           => function () { return new Helper\Form\Input\Generic; },
    'hidden'         => function () { return new Helper\Form\Input\Value; },
    'image'          => function () { return new Helper\Form\Input\Generic; },
    'month'          => function () { return new Helper\Form\Input\Value; },
    'number'         => function () { return new Helper\Form\Input\Value; },
    'password'       => function () { return new Helper\Form\Input\Value; },
    'radio'          => function () { return new Helper\Form\Input\Checked; },
    'range'          => function () { return new Helper\Form\Input\Value; },
    'reset'          => function () { return new Helper\Form\Input\Generic; },
    'search'         => function () { return new Helper\Form\Input\Value; },
    'submit'         => function () { return new Helper\Form\Input\Generic; },
    'tel'            => function () { return new Helper\Form\Input\Value; },
    'text'           => function () { return new Helper\Form\Input\Value; },
    'time'           => function () { return new Helper\Form\Input\Value; },
    'url'            => function () { return new Helper\Form\Input\Value; },
    'week'           => function () { return new Helper\Form\Input\Value; },
];
