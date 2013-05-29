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
 * Aura\View\Helper\Form\Field
 */
$di->params['Aura\View\Helper\Form\Field']['registry'] = [
    'button'         => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'checkbox'       => $di->lazyNew('Aura\View\Helper\Form\Input\Checked'),
    'color'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'date'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'datetime'       => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'datetime-local' => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'email'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'file'           => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'hidden'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'image'          => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'month'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'number'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'password'       => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'radio'          => $di->lazyNew('Aura\View\Helper\Form\Input\Checked'),
    'range'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'reset'          => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'search'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'submit'         => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'tel'            => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'text'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'time'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'url'            => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'week'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'radios'         => $di->lazyNew('Aura\View\Helper\Form\Radios'),
    'select'         => $di->lazyNew('Aura\View\Helper\Form\Select'),
    'textarea'       => $di->lazyNew('Aura\View\Helper\Form\Textarea'),
    'repeat'         => $di->lazyNew('Aura\View\Helper\Form\Repeat'),
];

/**
 * Aura\View\Helper\Form\Input
 */
$di->params['Aura\View\Helper\Form\Input']['registry'] = [
    'button'         => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'checkbox'       => $di->lazyNew('Aura\View\Helper\Form\Input\Checked'),
    'color'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'date'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'datetime'       => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'datetime-local' => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'email'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'file'           => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'hidden'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'image'          => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'month'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'number'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'password'       => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'radio'          => $di->lazyNew('Aura\View\Helper\Form\Input\Checked'),
    'range'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'reset'          => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'search'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'submit'         => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'tel'            => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'text'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'time'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'url'            => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'week'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
];

/**
 * Aura\View\Helper\Repeat
 */
$di->params['Aura\View\Helper\Form\Repeat']['registry'] = [
    'button'         => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'checkbox'       => $di->lazyNew('Aura\View\Helper\Form\Input\Checked'),
    'color'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'date'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'datetime'       => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'datetime-local' => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'email'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'file'           => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'hidden'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'image'          => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'month'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'number'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'password'       => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'radio'          => $di->lazyNew('Aura\View\Helper\Form\Input\Checked'),
    'range'          => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'reset'          => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'search'         => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'submit'         => $di->lazyNew('Aura\View\Helper\Form\Input\Generic'),
    'tel'            => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'text'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'time'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'url'            => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'week'           => $di->lazyNew('Aura\View\Helper\Form\Input\Value'),
    'radios'         => $di->lazyNew('Aura\View\Helper\Form\Radios'),
    'select'         => $di->lazyNew('Aura\View\Helper\Form\Select'),
    'textarea'       => $di->lazyNew('Aura\View\Helper\Form\Textarea'),
];

/**
 * Aura\View\Helper\Radios
 */
$di->params['Aura\View\Helper\Form\Radios'] = [
    'input' => $di->lazyNew('Aura\View\Helper\Form\Input\Checked'),
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
    'field'       => $di->lazyNew('Aura\View\Helper\Form\Field'),
    'image'       => $di->lazyNew('Aura\View\Helper\Image'),
    'input'       => $di->lazyNew('Aura\View\Helper\Form\Input'),
    'links'       => $di->lazyNew('Aura\View\Helper\Links'),
    'metas'       => $di->lazyNew('Aura\View\Helper\Metas'),
    'ol'          => $di->lazyNew('Aura\View\Helper\Ol'),
    'radios'      => $di->lazyNew('Aura\View\Helper\Form\Radios'),
    'repeat'      => $di->lazyNew('Aura\View\Helper\Form\Repeat'),
    'scripts'     => $di->lazyNew('Aura\View\Helper\Scripts'),
    'scriptsFoot' => $di->lazyNew('Aura\View\Helper\Scripts'),
    'select'      => $di->lazyNew('Aura\View\Helper\Form\Select'),
    'styles'      => $di->lazyNew('Aura\View\Helper\Styles'),
    'tag'         => $di->lazyNew('Aura\View\Helper\Tag'),
    'title'       => $di->lazyNew('Aura\View\Helper\Title'),
    'textarea'    => $di->lazyNew('Aura\View\Helper\Form\Textarea'),
    'ul'          => $di->lazyNew('Aura\View\Helper\Ul'),
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
