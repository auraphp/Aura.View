<?php
namespace aura\view\helper;
use aura\view\Helper;

/**
 * 
 * Helper for <a ... /> tags.
 * 
 */
class Anchor extends AbstractHelper
{
    /**
     * 
     * Returns an anchor tag with the text escaped.
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
    public function __invoke($href, $text, array $attribs = array())
    {
        // escape the href and text
        $href = $this->escape($href);
        $text = $this->escape($text);
        
        // make sure we don't overwrite the href attribute
        unset($attribs['href']);
        $attr = $this->attribs($attribs);
        
        // build text and return
        return "<a href=\"$href\"$attr>$text</a>";
    }
}
