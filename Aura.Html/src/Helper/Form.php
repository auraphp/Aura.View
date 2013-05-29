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
 * Helper to generate an opening form tag.
 * 
 * @package Aura.Html
 * 
 */
class Form extends AbstractHelper
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
    public function __invoke(array $attribs = [])
    {
        $base = [
            'id' => null,
            'method' => 'post',
            'action' => null,
            'enctype' => 'multipart/form-data',
        ];
        
        $attribs = $this->strAttribs(array_merge($base, $attribs));
        return "<form $attribs>";
    }
}
