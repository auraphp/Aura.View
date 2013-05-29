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
 * An HTML button.
 * 
 * @package Aura.Html
 * 
 */
class Button extends AbstractElement
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
        // set the type
        $this->attribs['type'] = $this->type;
        
        // no values on buttons
        unset($this->attribs['value']);
        
        // build html
        return $this->indent(0, $this->void('input', $this->attribs));
    }
}
