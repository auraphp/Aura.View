<?php
use Aura\View\Helper;

return array_merge(
    require __DIR__ . '/field_registry.php',
    [
        'repeat'   => function () { return new Helper\Form\Repeat(require __DIR__ . '/field_registry.php') },
    ]
);
