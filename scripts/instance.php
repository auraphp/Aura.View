<?php
namespace aura\view;
use aura\di\Forge;
use aura\di\Config;
require_once dirname(__DIR__) . '/src.php';
return new Template(
    new Finder,
    new HelperRegistry(new Forge(new Config))
);
