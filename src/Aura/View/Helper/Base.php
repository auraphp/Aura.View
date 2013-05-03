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
 * Helper to generate `<base ... />` tags.
 * 
 * @package Aura.View
 * 
 */
class Base extends AbstractHelper
{
    /**
     * 
     * Returns a `<base ... />` tag.
     * 
     * @param string $href The base href.
     * 
     * @return string The `<base ... />` tag.
     * 
     */
    public function __invoke($href)
    {
        return $this->indent(1, $this->void('base', ['href' => $href]));
    }
}
