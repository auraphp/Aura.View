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
 * Helper for `<ol>` tag with `<li>` items.
 * 
 * @package Aura.View
 * 
 */
class Ol extends Ul
{
    /**
     * 
     * Returns the tag name.
     * 
     * @return string
     * 
     */
    public function getTag()
    {
        return 'ol';
    }
}
