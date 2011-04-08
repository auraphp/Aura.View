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
 * Helper to generate `<a ... />` tags.
 * 
 * @package aura.view
 * 
 */
class Anchor extends AbstractHelper
{
    /**
     * 
     * Returns an anchor tag with the anchor text escaped.
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
    
    /**
     * 
     * Returns an anchor tag but does not escape the text; suitable for
     * wrapping an anchor around other HTML, such as an image.
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
    public function raw($href, $text, array $attribs = array())
    {
        // escape the href, but *not* the text
        $href = $this->escape($href);
        
        // make sure we don't overwrite the href attribute
        unset($attribs['href']);
        $attr = $this->attribs($attribs);
        
        // build text and return
        return "<a href=\"$href\"$attr>$text</a>";
    }
}
