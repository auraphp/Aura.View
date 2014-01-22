<?php
/**
 * Aura\View\Manager
 */
$di->params['Aura\View\Manager'] = [
    'template'       => $di->lazyNew('Aura\View\Template'),
    'helper'        => $di->lazyNew('Aura\Html\HelperLocator'),
    'view_finder'   => $di->lazyNew('Aura\View\Finder'),
    'layout_finder' => $di->lazyNew('Aura\View\Finder'),
];
