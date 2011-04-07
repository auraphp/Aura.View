<?php
$di->params['aura\view\AbstractTemplate']['helper_registry'] = $di->lazyNew('aura\view\HelperRegistry');

$di->params['aura\view\helper\Datetime']['format']['date'] = 'Y-m-d H:i:s';
$di->params['aura\view\helper\Datetime']['format']['time'] = 'H:i:s';
$di->params['aura\view\helper\Datetime']['format']['datetime'] = 'Y-m-d H:i:s';
$di->params['aura\view\helper\Datetime']['format']['default'] = 'Y-m-d H:i:s';

$di->setter['aura\view\helper\AbstractHelper']['setEscapeQuotes']  = ENT_COMPAT;
$di->setter['aura\view\helper\AbstractHelper']['setEscapeCharset'] = 'UTF-8';

