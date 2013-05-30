<?php
namespace Aura\Html;

include dirname(__DIR__) . '/autoload.php';

return new HelperLocator(new HelperFactory(
    new Escape,
    require __DIR__ . '/helper_registry.php'
));
