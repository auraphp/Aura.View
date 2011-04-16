<?php
namespace aura\view;
use aura\di\Container;
use aura\di\Forge;
use aura\di\Config;
require_once dirname(__DIR__) . '/src.php';

// create and set services on a view helper container
$vhc = new Container(new Forge(new Config));

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

return new Template(new Finder, $vhc);
