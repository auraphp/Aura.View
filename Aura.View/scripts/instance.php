<?php
namespace Aura\View;

require_once dirname(__DIR__) . '/autoload.php';

return new Manager(
    new Factory,    // template factory
    new Helper,     // helper object
    new Finder,     // view finder
    new Finder      // layout finder
);
