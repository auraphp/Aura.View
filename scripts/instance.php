<?php
namespace Aura\View;
require_once dirname(__DIR__) . '/src.php';
return new Template(new EscaperFactory, new TemplateFinder, new HelperLocator([
    'anchor'        =>  function() { return new \Aura\View\Helper\Anchor; },
    'attribs'       =>  function() { return new \Aura\View\Helper\Attribs; },
    'base'          =>  function() { return new \Aura\View\Helper\Base; },
    'datetime'      =>  function() { return new \Aura\View\Helper\Datetime; },
    'escape'        =>  function() { return new \Aura\View\Helper\Escape(new EscaperFactory); },
    'image'         =>  function() { return new \Aura\View\Helper\Image; },
    'links'         =>  function() { return new \Aura\View\Helper\Links; },
    'metas'         =>  function() { return new \Aura\View\Helper\Metas; },
    'scripts'       =>  function() { return new \Aura\View\Helper\Scripts; },
    'scriptsFoot'   =>  function() { return new \Aura\View\Helper\Scripts; },
    'styles'        =>  function() { return new \Aura\View\Helper\Styles; },
    'title'         =>  function() { return new \Aura\View\Helper\Title; },
    'formText'         =>  function() { return new \Aura\View\Helper\FormText; },
]));
