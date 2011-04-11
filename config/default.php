<?php

// params for Template instances
$di->params['aura\view\Template']['helper_container'] = $di->lazyGet('view_helper_container');
$di->params['aura\view\Template']['finder'] = $di->lazyNew('aura\view\Finder');

// escaping params
$di->setter['aura\view\helper\AbstractHelper']['setEscapeQuotes']  = ENT_COMPAT;
$di->setter['aura\view\helper\AbstractHelper']['setEscapeCharset'] = 'UTF-8';

// date-time formats
$di->params['aura\view\helper\Datetime']['format']['date'] = 'Y-m-d H:i:s';
$di->params['aura\view\helper\Datetime']['format']['time'] = 'H:i:s';
$di->params['aura\view\helper\Datetime']['format']['datetime'] = 'Y-m-d H:i:s';
$di->params['aura\view\helper\Datetime']['format']['default'] = 'Y-m-d H:i:s';

// view helper container, and services for that container
$vhc = $di->newContainer();

$vhc->set('anchor', function() use ($di) {
    return $di->newInstance('aura\view\helper\Anchor');
});

$vhc->set('attribs', function() use ($di) {
    return $di->newInstance('aura\view\helper\Attribs');
});

$vhc->set('base', function() use ($di) {
    return $di->newInstance('aura\view\helper\Base');
});

$vhc->set('datetime', function() use ($di) {
    return $di->newInstance('aura\view\helper\Datetime');
});

$vhc->set('escape', function() use ($di) {
    return $di->newInstance('aura\view\helper\Escape');
});

$vhc->set('image', function() use ($di) {
    return $di->newInstance('aura\view\helper\Image');
});

$vhc->set('links', function() use ($di) {
    return $di->newInstance('aura\view\helper\Links');
});

$vhc->set('metas', function() use ($di) {
    return $di->newInstance('aura\view\helper\Metas');
});

$vhc->set('scripts', function() use ($di) {
    return $di->newInstance('aura\view\helper\Scripts');
});

$vhc->set('scriptsFoot', function() use ($di) {
    return $di->newInstance('aura\view\helper\Scripts');
});

$vhc->set('styles', function() use ($di) {
    return $di->newInstance('aura\view\helper\Styles');
});

$vhc->set('title', function() use ($di) {
    return $di->newInstance('aura\view\helper\Title');
});

// retain the view helper container
$di->set('view_helper_container', $vhc);
