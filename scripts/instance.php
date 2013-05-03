<?php
namespace Aura\View;
require_once dirname(__DIR__) . '/src.php';
return new Template(new EscaperFactory, new TemplateFinder, new HelperLocator([
    'anchor'        => function () { return new Helper\Anchor; },
    'attribs'       => function () { return new Helper\Attribs; },
    'base'          => function () { return new Helper\Base; },
    'datetime'      => function () { return new Helper\Datetime; },
    'escape'        => function () { return new Helper\Escape(new EscaperFactory); },
    'field'         => function () { return new Helper\Form\Field(require __DIR__ . '/field_registry.php'); },
    'image'         => function () { return new Helper\Image; },
    'input'         => function () { return new Helper\Form\Input(require __DIR__ . '/input_registry.php'); },
    'links'         => function () { return new Helper\Links; },
    'metas'         => function () { return new Helper\Metas; },
    'ol'            => function () { return new Helper\Ol; },
    'radios'        => function () { return new Helper\Form\Radios(new Helper\Form\Input\Checked); },
    'repeat'        => function () { return new Helper\Form\Repeat(require __DIR__ . '/repeat_registry.php'); },
    'scripts'       => function () { return new Helper\Scripts; },
    'scriptsFoot'   => function () { return new Helper\Scripts; },
    'select'        => function () { return new Helper\Form\Select; },
    'styles'        => function () { return new Helper\Styles; },
    'tag'           => function () { return new Helper\Tag; },
    'title'         => function () { return new Helper\Title; },
    'textarea'      => function () { return new Helper\Form\Textarea; },
    'ul'            => function () { return new Helper\Ul; },
]));
