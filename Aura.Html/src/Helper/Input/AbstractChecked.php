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
 * Abstact helper for inputs that can be checked (e.g. radio or checkbox).
 * 
 * @package Aura.Html
 * 
 */
abstract class AbstractChecked extends AbstractInput
{
    /**
     * 
     * The label for the input, if any.
     * 
     * @var string
     * 
     */
    protected $label;
    
    /**
     * 
     * Returns the HTML for the "checked" part of the input.
     * 
     * @return string
     * 
     */
    protected function htmlChecked()
    {
        // extract and retain the 'label' pseudo-attribute
        $this->label = null;
        if (isset($this->attribs['label'])) {
            $this->label = $this->attribs['label'];
            unset($this->attribs['label']);
        }
        
        // by default, the input is unchecked
        $this->attribs['checked'] = false;
        
        // is the input checked? make sure there's a value to compare to, and
        // use strict equality so that there is no confusion between
        // 0/'0'/false/null/''.
        $checked = isset($this->attribs['value'])
                && $this->value === $this->attribs['value'];
        if ($checked) {
            $this->attribs['checked'] = true;
        }
        
        // build the HTML for the input
        return $this->void('input', $this->attribs);
    }
    
    /**
     * 
     * Returns the HTML for a "label" (if any) wrapped around the input.
     * 
     * @return string
     * 
     */
    protected function htmlLabel($input)
    {
        if (! $this->label) {
            return $input;
        }
        
        if (isset($this->attribs['id'])) {
            $attribs = $this->escaper->attr(['for' => $this->attribs['id']]);
            return "<label {$attribs}>{$input} {$this->label}</label>";
        } else {
            return "<label>{$input} {$this->label}</label>";
        }
    }
}
