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
 * Helper to generate `<base ... />` tags.
 * 
 * @package aura.view
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
        $href = $this->escape($href);
        return $this->indent . "<base href=\"$href\" />" . PHP_EOL;
    }
}
