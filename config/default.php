<?php
$di->params['aura\view\AbstractTemplate']['helper_registry'] = $di->lazyNew('aura\view\HelperRegistry');

$di->params['aura\view\helper\Datetime']['format']['date'] = 'Y-m-d H:i:s';
$di->params['aura\view\helper\Datetime']['format']['time'] = 'H:i:s';
$di->params['aura\view\helper\Datetime']['format']['datetime'] = 'Y-m-d H:i:s';
$di->params['aura\view\helper\Datetime']['format']['default'] = 'Y-m-d H:i:s';

$di->setter['aura\view\helper\AbstractHelper']['setEscapeQuotes']  = ENT_COMPAT;
$di->setter['aura\view\helper\AbstractHelper']['setEscapeCharset'] = 'UTF-8';

$di->params['aura\view\HelperRegistry']['map']['anchor']      = 'aura\view\helper\Anchor';
$di->params['aura\view\HelperRegistry']['map']['attribs']     = 'aura\view\helper\Attribs';
$di->params['aura\view\HelperRegistry']['map']['base']        = 'aura\view\helper\Base';
$di->params['aura\view\HelperRegistry']['map']['datetime']    = 'aura\view\helper\Datetime';
$di->params['aura\view\HelperRegistry']['map']['escape']      = 'aura\view\helper\Escape';
$di->params['aura\view\HelperRegistry']['map']['image']       = 'aura\view\helper\Image';
$di->params['aura\view\HelperRegistry']['map']['links']       = 'aura\view\helper\Links';
$di->params['aura\view\HelperRegistry']['map']['metas']       = 'aura\view\helper\Metas';
$di->params['aura\view\HelperRegistry']['map']['scripts']     = 'aura\view\helper\Scripts';
$di->params['aura\view\HelperRegistry']['map']['scriptsFoot'] = 'aura\view\helper\Scripts';
$di->params['aura\view\HelperRegistry']['map']['styles']      = 'aura\view\helper\Styles';
$di->params['aura\view\HelperRegistry']['map']['title']       = 'aura\view\helper\Title';
