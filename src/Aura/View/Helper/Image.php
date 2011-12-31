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
 * Helper to generate an <img ... /> tag.
 * 
 */
class Image extends AbstractHelper
{
    /**
     * 
     * Returns an <img ... /> tag.
     * 
     * If an "alt" attribute is not specified, will add it from the
     * image [[php::basename() | ]].
     * 
     * @param string $src The href to the image source.
     * 
     * @param array $attribs Additional attributes for the tag.
     * 
     * @return string An <img ... /> tag.
     * 
     * @todo Add automated height/width calculation?
     * 
     */
    public function __invoke($src, $attribs = [])
    {
        unset($attribs['src']);
        if (empty($attribs['alt'])) {
            $attribs['alt'] = basename($src);
        }
        
        return '<img src="' . $this->escape($src) . '" '
             . $this->attribs($attribs) . ' />';
    }
}
