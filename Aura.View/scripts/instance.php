<?php
namespace Aura\View;

require_once dirname(__DIR__) . '/autoload.php';

return new Manager(
    new Template,   // the template object
    new Helper,     // helper object for the template
    new Finder,     // view finder
    new Finder      // layout finder
);
