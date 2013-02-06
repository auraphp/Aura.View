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
 * Helper for a `<textarea>` tag.
 * 
 * @package Aura.View
 * 
 */
class Textarea extends AbstractHelper
{
    /**
     * 
     * Returns a `<textarea>`.
     * 
     * @param array $attribs Attributes for the textarea tag.
     * 
     * @param mixed $html The contents of the textarea tag.
     * 
     * @return string
     * 
     */
    public function __invoke(
        $attribs,
        $html = null
    ) {
        $attr = $this->attribs($attribs);
        return "<textarea {$attr}>$html</textarea>";
    }
}
