<?php
/**
 * Aura\View\Manager
 */
$di->params['Aura\View\Manager'] = [
    'factory'       => $di->lazyNew('Aura\View\Factory'),
    'helper'        => $di->lazyNew('Aura\Html\Helper'),
    'view_finder'   => $di->lazyNew('Aura\View\Finder'),
    'layout_finder' => $di->lazyNew('Aura\View\Finder'),
];
