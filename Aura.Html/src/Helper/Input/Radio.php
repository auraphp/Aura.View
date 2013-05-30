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
 * An HTML radio input.
 * 
 * @package Aura.Html
 * 
 */
class Radio extends AbstractChecked
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
        $this->attribs['type'] = 'radio';
        
        if ($this->options) {
            return $this->multiple();
        }
        
        $input = $this->htmlChecked();
        $html  = $this->htmlLabel($input);
        return $this->indent(0, $html);
    }
    
    protected function multiple()
    {
        $html = '';
        $radio = clone($this);
        foreach ($this->options as $value => $label) {
            $this->attribs['value'] = $value;
            $this->attribs['label'] = $label;
            $html .= $radio([
                'name'    => $this->name,
                'value'   => $this->value,
                'attribs' => $this->attribs
            ]);
        }
        return $html;
    }
}
