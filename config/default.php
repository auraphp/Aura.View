<?php
$di->setter['aura\view\TemplateBase']['setEscapeQuotes']  = ENT_COMPAT;
$di->setter['aura\view\TemplateBase']['setEscapeCharset'] = 'UTF-8';
$di->setter['aura\view\Plugin']['setEscapeQuotes']  = ENT_COMPAT;
$di->setter['aura\view\Plugin']['setEscapeCharset'] = 'UTF-8';

$di->params['aura\view\Template']['plugin_registry'] = $di->lazyNew('aura\view\PluginRegistry');
