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
     * @param mixed $raw The raw attribute key, value, or array of key-value
     * pairs.
     * 
     * @return string The escaped attribute string.
     * 
     */
    public function __invoke($raw)
    {
        return $this->escaper->attr($raw);
    }
}
