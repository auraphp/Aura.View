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
 * Helper to generate `<a ... />` tags.
 * 
 * @package Aura.View
 * 
 */
class Anchor extends AbstractHelper
{
    /**
     * 
     * Returns an anchor tag.
     * 
     * @param string $href The anchor href specification.
     * 
     * @param string $text The text for the anchor.
     * 
     * @param array $attribs Attributes for the anchor.
     * 
     * @return string
     * 
     */
    public function __invoke($href, $text, $attribs = [])
    {
        // build text and return
        if ($attribs) {
            $skip = ['href'];
            $attr = $this->attribs($attribs, $skip);
            return "<a href=\"$href\" $attr>$text</a>";
        } else {
            return "<a href=\"$href\">$text</a>";
        }
    }
}
