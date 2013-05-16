<?php
namespace Aura\View;
return array_merge(
    require __DIR__ . '/input_registry.php',
    [
        'radios'     => function () { return new Helper\Form\Radios(new Helper\Form\Input\Checked); },
        'select'     => function () { return new Helper\Form\Select; },
        'textarea'   => function () { return new Helper\Form\Textarea; },
    ]
);
