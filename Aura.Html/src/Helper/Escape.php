<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.Html
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Html\Helper;

/**
 * 
 * Helper to convert key-value arrays to attribute strings.
 * 
 * @package Aura.Html
 * 
 */
class Escape extends AbstractHelper
{
    /**
     * 
     * Returns the escaper object.
     * 
     * @return Escaper
     * 
     */
    public function __invoke()
    {
        return $this->escaper;
    }
}
