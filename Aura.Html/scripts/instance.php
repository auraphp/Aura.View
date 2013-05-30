<?php
namespace Aura\Html;

include dirname(__DIR__) . '/autoload.php';

return new HelperLocator(
    new HelperFactory(
        new Escape(
            new Escape\AttrStrategy,
            new Escape\CssStrategy,
            new Escape\HtmlStrategy,
            new Escape\JsStrategy
        ),
        require __DIR__ . '/helper_registry.php'
    )
);
