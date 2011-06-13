<?php
// params for Template instances
$di->params['Aura\View\Template']['helper_container'] = $di->lazyCloneContainer('view_helper');
$di->params['Aura\View\Template']['finder'] = $di->lazyNew('Aura\View\Finder');

// params for TwoStep instances
$di->params['Aura\View\TwoStep']['template'] = $di->lazyNew('Aura\View\Template');

// create a new sub-container
$vhc = $di->newContainer('view_helper');

// date-time formats
$vhc->params['Aura\View\helper\Datetime']['format']['date'] = 'Y-m-d H:i:s';
$vhc->params['Aura\View\helper\Datetime']['format']['time'] = 'H:i:s';
$vhc->params['Aura\View\helper\Datetime']['format']['datetime'] = 'Y-m-d H:i:s';
$vhc->params['Aura\View\helper\Datetime']['format']['default'] = 'Y-m-d H:i:s';

// escaping values
$vhc->setter['Aura\View\helper\AbstractHelper']['setEscapeQuotes']  = ENT_COMPAT;
$vhc->setter['Aura\View\helper\AbstractHelper']['setEscapeCharset'] = 'UTF-8';

// set services on the sub-container
$vhc->set('anchor', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Anchor');
});

$vhc->set('attribs', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Attribs');
});

$vhc->set('base', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Base');
});

$vhc->set('datetime', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Datetime');
});

$vhc->set('escape', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Escape');
});

$vhc->set('image', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Image');
});

$vhc->set('links', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Links');
});

$vhc->set('metas', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Metas');
});

$vhc->set('scripts', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Scripts');
});

$vhc->set('scriptsFoot', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Scripts');
});

$vhc->set('styles', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Styles');
});

$vhc->set('title', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\helper\Title');
});
