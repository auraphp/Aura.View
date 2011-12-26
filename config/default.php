<?php
/**
 * Package prefix for autoloader.
 */
$loader->addPrefix('Aura\View\\', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');

// params for Template instances
$di->params['Aura\View\Template']['helper_locator'] = $di->lazyNew('Aura\View\HelperLocator');
$di->params['Aura\View\Template']['template_finder'] = $di->lazyNew('Aura\View\TemplateFinder');

// params for TwoStep instances
$di->params['Aura\View\TwoStep']['template'] = $di->lazyNew('Aura\View\Template');

// date-time formats
$di->params['Aura\View\Helper\Datetime']['format']['date'] = 'Y-m-d H:i:s';
$di->params['Aura\View\Helper\Datetime']['format']['time'] = 'H:i:s';
$di->params['Aura\View\Helper\Datetime']['format']['datetime'] = 'Y-m-d H:i:s';
$di->params['Aura\View\Helper\Datetime']['format']['default'] = 'Y-m-d H:i:s';

// escaping values
$di->setter['Aura\View\Helper\AbstractHelper']['setEscapeQuotes']  = ENT_COMPAT;
$di->setter['Aura\View\Helper\AbstractHelper']['setEscapeCharset'] = 'UTF-8';

// params for HelperLocator instances
$di->params['Aura\View\HelperLocator']['registry']['anchor'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Anchor');
};

$di->params['Aura\View\HelperLocator']['registry']['attribs'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Attribs');
};

$di->params['Aura\View\HelperLocator']['registry']['base'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Base');
};

$di->params['Aura\View\HelperLocator']['registry']['datetime'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Datetime');
};

$di->params['Aura\View\HelperLocator']['registry']['escape'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Escape');
};

$di->params['Aura\View\HelperLocator']['registry']['image'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Image');
};

$di->params['Aura\View\HelperLocator']['registry']['links'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Links');
};

$di->params['Aura\View\HelperLocator']['registry']['metas'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Metas');
};

$di->params['Aura\View\HelperLocator']['registry']['scripts'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Scripts');
};

$di->params['Aura\View\HelperLocator']['registry']['scriptsFoot'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Scripts');
};

$di->params['Aura\View\HelperLocator']['registry']['styles'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Styles');
};

$di->params['Aura\View\HelperLocator']['registry']['title'] = function() use ($di) {
    return $di->newInstance('Aura\View\Helper\Title');
};
