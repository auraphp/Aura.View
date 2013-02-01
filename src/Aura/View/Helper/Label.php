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
 * Helper for a `<label>` tag.
 * 
 * @package Aura.View
 * 
 */
class Label extends AbstractHelper
{
    /**
     * 
     * Returns a `<textarea>`.
     * 
     * @param mixed $html The contents of the label tag.
     * 
     * @param array $attribs Attributes for the label tag.
     * 
     * @return string
     * 
     */
    public function __invoke(
        $html,
        $attribs = []
    ) {
        $attr = $this->attribs($attribs);
        return "<label {$attr}>$html</label>";
    }
}
