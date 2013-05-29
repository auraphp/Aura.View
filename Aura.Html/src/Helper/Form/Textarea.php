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
namespace Aura\Html\Helper\Form;

/**
 * 
 * An HTML textarea element.
 * 
 * @package Aura.Html
 * 
 */
class Textarea extends AbstractElement
{
    /**
     * 
     * Returns the HTML for the element.
     * 
     * @return string
     * 
     */
    protected function html()
    {
        $attribs = $this->attribs($this->attribs);
        return "<textarea {$attribs}>{$this->value}</textarea>";
    }
}
