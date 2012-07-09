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
namespace Aura\View\Helper;

/**
 * 
 * Helper to convert key-value arrays to attribute strings.
 * 
 * @package Aura.View
 * 
 */
class Attribs extends AbstractHelper
{
    /**
     * 
     * Converts an associative array to an attribute string.
     * 
     * @param array $attribs From this array, each key-value pair is 
     * converted to an attribute name and value.
     * 
     * @param array $skip Skip attributes listed in this array.
     * 
     * @return string The string of attributes.
     * 
     */
    public function __invoke($attribs, array $skip = [])
    {
        return $this->attribs($attribs, $skip);
    }
}
