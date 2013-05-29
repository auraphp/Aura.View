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
 * Helper for `<ol>` tag with `<li>` items.
 * 
 * @package Aura.Html
 * 
 */
class Ol extends AbstractList
{
    /**
     * 
     * Returns the tag name.
     * 
     * @return string
     * 
     */
    protected function getTag()
    {
        return 'ol';
    }
}
