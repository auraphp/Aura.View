<?php
namespace Aura\View;
require_once dirname(__DIR__) . '/src.php';
return new Template(new EscaperFactory, new TemplateFinder, new HelperLocator([
    'anchor'        =>  function () { return new Helper\Anchor; },
    'attribs'       =>  function () { return new Helper\Attribs; },
    'base'          =>  function () { return new Helper\Base; },
    'datetime'      =>  function () { return new Helper\Datetime; },
    'escape'        =>  function () { return new Helper\Escape(new EscaperFactory); },
    'field'         =>  function () {
        return new Helper\Field(
            new Helper\Input,
            new Helper\Radios(new Helper\Input),
            new Helper\Select,
            new Helper\Textarea
        );
    },
    'image'         =>  function () { return new Helper\Image; },
    'input'         =>  function () {
        return new Helper\Input(
            new Helper\Input\Locator([
                'button'         => function () { return new Helper\Input\Button; },
                'checkbox'       => function () { return new Helper\Input\Checked; },
                'color'          => function () { return new Helper\Input\Value; },
                'date'           => function () { return new Helper\Input\Value; },
                'datetime'       => function () { return new Helper\Input\Value; },
                'datetime-local' => function () { return new Helper\Input\Value; },
                'email'          => function () { return new Helper\Input\Value; },
                'file'           => function () { return new Helper\Input\Button; },
                'hidden'         => function () { return new Helper\Input\Value; },
                'image'          => function () { return new Helper\Input\Button; },
                'month'          => function () { return new Helper\Input\Value; },
                'number'         => function () { return new Helper\Input\Value; },
                'password'       => function () { return new Helper\Input\Value; },
                'radio'          => function () { return new Helper\Input\Checked; },
                'range'          => function () { return new Helper\Input\Value; },
                'reset'          => function () { return new Helper\Input\Button; },
                'search'         => function () { return new Helper\Input\Value; },
                'submit'         => function () { return new Helper\Input\Button; },
                'tel'            => function () { return new Helper\Input\Value; },
                'text'           => function () { return new Helper\Input\Value; },
                'time'           => function () { return new Helper\Input\Value; },
                'url'            => function () { return new Helper\Input\Value; },
                'week'           => function () { return new Helper\Input\Value; },
            ])
        );
    },
    'links'         =>  function () { return new Helper\Links; },
    'metas'         =>  function () { return new Helper\Metas; },
    'ol'            =>  function () { return new Helper\Ol; },
    'radios'        =>  function () { return new Helper\Radios(new Helper\Input\Checked); },
    'scripts'       =>  function () { return new Helper\Scripts; },
    'scriptsFoot'   =>  function () { return new Helper\Scripts; },
    'select'        =>  function () { return new Helper\Select; },
    'styles'        =>  function () { return new Helper\Styles; },
    'tag'           =>  function () { return new Helper\Tag; },
    'title'         =>  function () { return new Helper\Title; },
    'textarea'      =>  function () { return new Helper\Textarea; },
    'ul'            =>  function () { return new Helper\Ul; },
]));
