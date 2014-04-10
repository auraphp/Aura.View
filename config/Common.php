<?php
namespace Aura\View\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {        
        $di->params['Aura\View\Manager'] = [
            'template'       => $di->lazyNew('Aura\View\Template'),
            'helper'        => $di->lazyNew('Aura\Html\HelperLocator'),
            'view_finder'   => $di->lazyNew('Aura\View\Finder'),
            'layout_finder' => $di->lazyNew('Aura\View\Finder'),
        ];
    }

    public function modify(Container $di)
    {
    }
}
