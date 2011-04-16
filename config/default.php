<?php
// params for Template instances
$di->params['aura\view\Template']['helper_container'] = $di->lazyCloneContainer('view_helper');
$di->params['aura\view\Template']['finder'] = $di->lazyNew('aura\view\Finder');

// create a new sub-container
$vhc = $di->newContainer('view_helper');

// date-time formats
$vhc->params['aura\view\helper\Datetime']['format']['date'] = 'Y-m-d H:i:s';
$vhc->params['aura\view\helper\Datetime']['format']['time'] = 'H:i:s';
$vhc->params['aura\view\helper\Datetime']['format']['datetime'] = 'Y-m-d H:i:s';
$vhc->params['aura\view\helper\Datetime']['format']['default'] = 'Y-m-d H:i:s';

// escaping values
$vhc->setter['aura\view\helper\AbstractHelper']['setEscapeQuotes']  = ENT_COMPAT;
$vhc->setter['aura\view\helper\AbstractHelper']['setEscapeCharset'] = 'UTF-8';

// set services on the sub-container
$vhc->set('anchor', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Anchor');
});

$vhc->set('attribs', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Attribs');
});

$vhc->set('base', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Base');
});

$vhc->set('datetime', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Datetime');
});

$vhc->set('escape', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Escape');
});

$vhc->set('image', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Image');
});

$vhc->set('links', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Links');
});

$vhc->set('metas', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Metas');
});

$vhc->set('scripts', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Scripts');
});

$vhc->set('scriptsFoot', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Scripts');
});

$vhc->set('styles', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Styles');
});

$vhc->set('title', function() use ($vhc) {
    return $vhc->newInstance('aura\view\helper\Title');
});
