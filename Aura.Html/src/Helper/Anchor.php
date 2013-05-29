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
 * Helper to generate `<a ... />` tags.
 * 
 * @package Aura.Html
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
    public function __invoke($href, $text, array $attribs = [])
    {
        // build text and return
        if ($attribs) {
            $skip = ['href'];
            $attribs = $this->attribs($attribs, $skip);
            return "<a href=\"$href\" $attribs>$text</a>";
        } else {
            return "<a href=\"$href\">$text</a>";
        }
    }
}
