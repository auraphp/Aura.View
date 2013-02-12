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
 * @package Aura.View
 * 
 */
class Image extends AbstractHelper
{
    /**
     * 
     * Returns an <img ... /> tag.
     * 
     * If an "alt" attribute is not specified, will add it from the
     * image basename.
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
        $attribs['src'] = $src;
        if (empty($attribs['alt'])) {
            $attribs['alt'] = basename($src);
        }

        return $this->void('img', $attribs);
    }
}
