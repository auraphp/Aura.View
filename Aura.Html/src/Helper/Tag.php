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
 * Helper to generate any tag.
 * 
 * @package Aura.Html
 * 
 */
class Tag extends AbstractHelper
{
    /**
     * 
     * Returns any kind of tag with attributes.
     * 
     * @param string $tag The tag to generate.
     * 
     * @param array $attribs Attributes for the tag.
     * 
     * @return string
     * 
     */
    public function __invoke($tag, array $attribs = [])
    {
        $attribs = $this->strAttribs($attribs);
        if ($attribs) {
            return "<{$tag} $attribs>";
        }
        return "<{$tag}>";
    }
}
