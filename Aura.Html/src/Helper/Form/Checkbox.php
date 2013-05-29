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
 * An HTML checkbox element.
 * 
 * @package Aura.Html
 * 
 */
class Checkbox extends AbstractChecked
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
        $input = $this->htmlUnchecked() . $this->htmlChecked();
        $html  = $this->htmlLabel($input);
        return $this->indent(0, $html);
    }
    
    /**
     * 
     * Returns the HTML for the "unchecked" part of the input.
     * 
     * @return string
     * 
     */
    protected function htmlUnchecked()
    {
        if (! isset($this->attribs['value_unchecked'])) {
            return;
        }
        
        $unchecked = $this->attribs['value_unchecked'];
        unset($this->attribs['value_unchecked']);
        
        $attribs = [
            'type' => 'hidden',
            'value' => $unchecked,
        ];
        
        return $this->void('input', $attribs);
    }
}
