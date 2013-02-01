<?php
namespace Aura\View;
require_once dirname(__DIR__) . '/src.php';
return new Template(new EscaperFactory, new TemplateFinder, new HelperLocator([
    'anchor'        =>  function () { return new Helper\Anchor; },
    'attribs'       =>  function () { return new Helper\Attribs; },
    'base'          =>  function () { return new Helper\Base; },
    'datetime'      =>  function () { return new Helper\Datetime; },
    'escape'        =>  function () { return new Helper\Escape(new EscaperFactory); },
    'image'         =>  function () { return new Helper\Image; },
    'input'         =>  function () { return new Helper\Input; },
    'links'         =>  function () { return new Helper\Links; },
    'metas'         =>  function () { return new Helper\Metas; },
    'radios'        =>  function () { return new Helper\Escape(new Helper\Input); },
    'scripts'       =>  function () { return new Helper\Scripts; },
    'scriptsFoot'   =>  function () { return new Helper\Scripts; },
    'select'        =>  function () { return new Helper\Select; },
    'styles'        =>  function () { return new Helper\Styles; },
    'title'         =>  function () { return new Helper\Title; },
    'textarea'      =>  function () { return new Helper\Textarea; },
]));
