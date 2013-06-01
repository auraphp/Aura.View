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
class EscapeAttr extends AbstractHelper
{
    /**
     * 
     * Converts an associative array to an attribute string.
     * 
     * @param array $attr From this array, each key-value pair is 
     * converted to an attribute name and value.
     * 
     * @return string The string of attributes.
     * 
     */
    public function __invoke(array $attr)
    {
        return $this->escaper->attr($attr);
    }
}
