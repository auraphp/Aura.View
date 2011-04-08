<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace aura\view\helper;

/**
 * 
 * Helper to escape attributes.
 * 
 * @package aura.view
 * 
 */
class Attribs extends AbstractHelper
{
    /**
     * 
     * Converts an associative array to an escaped attribute string.
     * 
     * @param array $attribs From this array, each key-value pair is 
     * converted to an attribute name and value.
     * 
     * @return string The escaped attributes string.
     * 
     */
    public function __invoke(array $attribs)
    {
        return $this->attribs($attribs);
    }
}
