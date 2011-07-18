<?php
// params for Template instances
$di->params['Aura\View\Template']['helper_container'] = $di->lazyCloneContainer('view_helper');
$di->params['Aura\View\Template']['finder'] = $di->lazyNew('Aura\View\Finder');

// params for TwoStep instances
$di->params['Aura\View\TwoStep']['template'] = $di->lazyNew('Aura\View\Template');

// create a new sub-container
$vhc = $di->newContainer('view_helper');

// date-time formats
$vhc->params['Aura\View\Helper\Datetime']['format']['date'] = 'Y-m-d H:i:s';
$vhc->params['Aura\View\Helper\Datetime']['format']['time'] = 'H:i:s';
$vhc->params['Aura\View\Helper\Datetime']['format']['datetime'] = 'Y-m-d H:i:s';
$vhc->params['Aura\View\Helper\Datetime']['format']['default'] = 'Y-m-d H:i:s';

// escaping values
$vhc->setter['Aura\View\Helper\AbstractHelper']['setEscapeQuotes']  = ENT_COMPAT;
$vhc->setter['Aura\View\Helper\AbstractHelper']['setEscapeCharset'] = 'UTF-8';

// set services on the sub-container
$vhc->set('anchor', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Anchor');
});

$vhc->set('attribs', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Attribs');
});

$vhc->set('base', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Base');
});

$vhc->set('datetime', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Datetime');
});

$vhc->set('escape', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Escape');
});

$vhc->set('image', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Image');
});

$vhc->set('links', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Links');
});

$vhc->set('metas', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Metas');
});

$vhc->set('scripts', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Scripts');
});

$vhc->set('scriptsFoot', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Scripts');
});

$vhc->set('styles', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Styles');
});

$vhc->set('title', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Title');
});

//params for Route which is a route_map object
$vhc->params['Aura\View\Helper\Route']['router_map'] = $di->lazyGet('router_map');

//Route can be only used in Aura.View when Aura.Router is used, 
//else will give fatal Error
$vhc->set('route', function() use ($vhc) {
    return $vhc->newInstance('Aura\View\Helper\Route');
});
