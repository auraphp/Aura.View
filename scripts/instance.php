<?php
namespace Aura\View;
use Aura\Di\Container;
use Aura\Di\Forge;
use Aura\Di\Config;
require_once dirname(__DIR__) . '/src.php';

// create and set services on a view helper container
$vhc = new Container(new Forge(new Config));

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

return new Template(new Finder, $vhc);
