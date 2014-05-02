<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.View
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View;

class ViewFactory
{
    public function newInstance($helpers = null)
    {
        if (! $helpers) {
            $helpers = new HelperRegistry;
        }
        
        return new View(
            new TemplateRegistry,
            new TemplateRegistry,
            $helpers
        );
    }
}
