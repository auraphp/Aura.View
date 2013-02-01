<?php
/**
 * Loader
 */
$loader->add('Aura\View\\', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');

/**
 * Aura\View\Helper\DateTime
 */
$di->params['Aura\View\Helper\Datetime']['format'] = [
    'date' => 'Y-m-d H:i:s',
    'time' => 'H:i:s',
    'datetime' => 'Y-m-d H:i:s',
    'default' => 'Y-m-d H:i:s',
];

/**
 * Aura\View\Helper\Escape
 */
$di->params['Aura\View\Helper\Escape'] = [
    'escaper_factory' => $di->lazyNew('Aura\View\EscaperFactory'),
];

/**
 * Aura\View\HelperLocator
 */
$di->params['Aura\View\HelperLocator']['registry'] = [
    'anchor'      => $di->lazyNew('Aura\View\Helper\Anchor'),
    'attribs'     => $di->lazyNew('Aura\View\Helper\Attribs'),
    'base'        => $di->lazyNew('Aura\View\Helper\Base'),
    'datetime'    => $di->lazyNew('Aura\View\Helper\Datetime'),
    'escape'      => $di->lazyNew('Aura\View\Helper\Escape'),
    'image'       => $di->lazyNew('Aura\View\Helper\Image'),
    'input'       => $di->lazyNew('Aura\View\Helper\Input'),
    'links'       => $di->lazyNew('Aura\View\Helper\Links'),
    'metas'       => $di->lazyNew('Aura\View\Helper\Metas'),
    'scripts'     => $di->lazyNew('Aura\View\Helper\Scripts'),
    'scriptsFoot' => $di->lazyNew('Aura\View\Helper\Scripts'),
    'select'      => $di->lazyNew('Aura\View\Helper\Select'),
    'styles'      => $di->lazyNew('Aura\View\Helper\Styles'),
    'title'       => $di->lazyNew('Aura\View\Helper\Title'),
];

/**
 * Aura\View\Template
 */
$di->params['Aura\View\Template'] = [
    'escaper_factory' => $di->lazyNew('Aura\View\EscaperFactory'),
    'helper_locator'  => $di->lazyNew('Aura\View\HelperLocator'),
    'template_finder' => $di->lazyNew('Aura\View\TemplateFinder'),
];

/**
 * Aura\View\TwoStep
 */
$di->params['Aura\View\TwoStep'] = [
    'template'     => $di->lazyNew('Aura\View\Template'),
    'format_types' => $di->lazyNew('Aura\View\FormatTypes'),
];
