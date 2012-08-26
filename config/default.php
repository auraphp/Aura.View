<?php
/**
 * Package prefix for autoloader.
 */
$loader->add('Aura\View\\', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');

/**
 * Instance params and setter values.
 */

// params for Template instances
$di->params['Aura\View\Template']['escaper_factory'] = $di->lazyNew('Aura\View\EscaperFactory');
$di->params['Aura\View\Template']['helper_locator'] = $di->lazyNew('Aura\View\HelperLocator');
$di->params['Aura\View\Template']['template_finder'] = $di->lazyNew('Aura\View\TemplateFinder');

// params for TwoStep instances
$di->params['Aura\View\TwoStep']['template'] = $di->lazyNew('Aura\View\Template');
$di->params['Aura\View\TwoStep']['format_types'] = $di->lazyNew('Aura\View\FormatTypes');

// date-time formats
$di->params['Aura\View\Helper\Datetime']['format']['date'] = 'Y-m-d H:i:s';
$di->params['Aura\View\Helper\Datetime']['format']['time'] = 'H:i:s';
$di->params['Aura\View\Helper\Datetime']['format']['datetime'] = 'Y-m-d H:i:s';
$di->params['Aura\View\Helper\Datetime']['format']['default'] = 'Y-m-d H:i:s';

// escaping
$di->params['Aura\View\Helper\Escape']['escaper_factory'] = $di->lazyNew('Aura\View\EscaperFactory');

// params for HelperLocator instances
$di->params['Aura\View\HelperLocator']['registry']['anchor'] = $di->lazyNew('Aura\View\Helper\Anchor');
$di->params['Aura\View\HelperLocator']['registry']['attribs'] = $di->lazyNew('Aura\View\Helper\Attribs');
$di->params['Aura\View\HelperLocator']['registry']['base'] = $di->lazyNew('Aura\View\Helper\Base');
$di->params['Aura\View\HelperLocator']['registry']['datetime'] = $di->lazyNew('Aura\View\Helper\Datetime');
$di->params['Aura\View\HelperLocator']['registry']['escape'] = $di->lazyNew('Aura\View\Helper\Escape');
$di->params['Aura\View\HelperLocator']['registry']['image'] = $di->lazyNew('Aura\View\Helper\Image');
$di->params['Aura\View\HelperLocator']['registry']['links'] = $di->lazyNew('Aura\View\Helper\Links');
$di->params['Aura\View\HelperLocator']['registry']['metas'] = $di->lazyNew('Aura\View\Helper\Metas');
$di->params['Aura\View\HelperLocator']['registry']['scripts'] = $di->lazyNew('Aura\View\Helper\Scripts');
$di->params['Aura\View\HelperLocator']['registry']['scriptsFoot'] = $di->lazyNew('Aura\View\Helper\Scripts');
$di->params['Aura\View\HelperLocator']['registry']['styles'] = $di->lazyNew('Aura\View\Helper\Styles');
$di->params['Aura\View\HelperLocator']['registry']['title'] = $di->lazyNew('Aura\View\Helper\Title');
