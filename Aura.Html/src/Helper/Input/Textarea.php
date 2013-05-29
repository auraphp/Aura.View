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
namespace Aura\Html\Helper\Input;

/**
 * 
 * An HTML textarea input.
 * 
 * @package Aura.Html
 * 
 */
class Textarea extends AbstractInput
{
    /**
     * 
     * Returns the HTML for the input.
     * 
     * @return string
     * 
     */
    protected function html()
    {
        $attribs = $this->strAttribs($this->attribs);
        return "<textarea {$attribs}>{$this->value}</textarea>";
    }
}
