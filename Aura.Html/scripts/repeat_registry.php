<?php
namespace Aura\View;
return array_merge(
    require __DIR__ . '/element_registry.php',
    [
        'repeat'   => function () { return new Helper\Form\Repeat(require __DIR__ . '/element_registry.php'); },
    ]
);
