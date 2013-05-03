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
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelper;

/**
 * 
 * Helper for `<select>` tag with `<option>` and `<optgroup>` tags.
 * 
 * @package Aura.View
 * 
 */
class Select extends AbstractHelper
{
    /**
     * 
     * A stack of HTML pieces for the select.
     * 
     * @var array
     * 
     */
    protected $stack = [];
    
    /**
     * 
     * The attributes for the select.
     * 
     * @var array
     * 
     */
    protected $attribs = [];
    
    /**
     * 
     * Are we currently processing an optgroup?
     * 
     * @var bool
     * 
     */
    protected $optgroup = false;
    
    /**
     * 
     * The value(s) that have been selected.
     * 
     * @var array
     * 
     */
    protected $selected = [];
    
    /**
     * 
     * The generated HTML for the select.
     * 
     * @var string
     * 
     */
    protected $html = '';
    
    /**
     * 
     * The current option indent level.
     * 
     * @var int
     * 
     */
    protected $optlevel = 1;
    
    /**
     * 
     * Returns a select tag with options.
     * 
     * @param array $attribs The select attributes.
     * 
     * @param array $options The options available within the select.
     * 
     * @param mixed $value The curently-selected value(s).
     * 
     * @return mixed The generated HTML if $options were passed, or this
     * select object if not.
     * 
     */
    public function __invoke($attribs, $options = [], $value = null)
    {
        // reset the properties
        $this->stack    = [];
        $this->optgroup = false;
        $this->selected = [];
        $this->html     = '';
        $this->attribs  = $attribs;
        $this->optlevel = 1;
        
        // handle a pseudo-attribute 'placeholder' for the select
        if (isset($this->attribs['placeholder'])) {
            $this->option(
                '',
                $this->attribs['placeholder']
            );
            unset($this->attribs['placeholder']);
        }
        
        // if there are options, process them; otherwise return this object
        if ($options) {
            $this->options($options);
            $this->selected($value);
            return $this->fetch();
        } else {
            return $this;
        }
    }
    
    /**
     * 
     * Given a field specification, returns a select tag with options.
     * 
     * @param array $spec The field specification.
     * 
     * @return string
     * 
     */
    public function getField($spec)
    {
        $attribs = isset($spec['attribs'])
                 ? $spec['attribs']
                 : [];
        
        $options = isset($spec['options'])
                 ? $spec['options']
                 : [];
        
        $value = isset($spec['value'])
               ? $spec['value']
               : null;
               
        if (isset($spec['name'])) {
            $attribs['name'] = $spec['name'];
        }
        
        return $this->__invoke($attribs, $options, $value);
    }
    
    /**
     * 
     * Adds a single option to the stack.
     * 
     * @param string $value The option value.
     * 
     * @param string $label The option label.
     * 
     * @param array $attribs Attributes for the option.
     * 
     * @return self
     * 
     */
    public function option($value, $label, $attribs = [])
    {
        $this->stack[] = ['buildOption', $value, $label, $attribs];
        return $this;
    }
    
    /**
     * 
     * Adds multiple options to the stack.
     * 
     * @param array $options An array of options where the key is the option
     * value, and the value is the option label.  If the value is an array,
     * the key is treated as a label for an optgroup, and the value is 
     * a sub-array of options.
     * 
     * @param array $attribs Attributes to be used on each option.
     * 
     * @return self
     * 
     */
    public function options($options, $attribs = [])
    {
        // set the options and optgroups
        foreach ($options as $key => $val) {
            $trav = is_array($val) || $val instanceof \Traversable;
            if ($trav) {
                // the key is an optgroup label
                $this->optgroup($key);
                // recursively descend into the array
                $this->options($val, $attribs);
            } else {
                // the key is an option value and the val is an option label
                $this->option($key, $val, $attribs);
            }
        }
        
        return $this;
    }
    
    /**
     * 
     * Adds an optgroup element to the stack.
     * 
     * @param string $label The optgroup label.
     * 
     * @param array $attribs Attributes for the optgroup.
     * 
     * @return self
     * 
     */
    public function optgroup($label, $attribs = [])
    {
        if ($this->optgroup) {
            $this->stack[] = ['endOptgroup'];
        }
        $this->stack[] = ['beginOptgroup', $label, $attribs];
        $this->optgroup = true;
        return $this;
    }
    
    /**
     * 
     * Sets the selected value(s).
     * 
     * @param mixed $selected The selected value(s).
     * 
     * @return self
     * 
     */
    public function selected($selected)
    {
        $trav = is_array($selected) || $selected instanceof \Traversable;
        if ($trav) {
            $this->selected = [];
            foreach ($selected as $key => $val) {
                $this->selected[$key] = $val;
            }
        } else {
            $this->selected = (array) $selected;
        }

        return $this;
    }
    
    /**
     * 
     * Returns the HTML generated from the stack.
     * 
     * @return string
     * 
     */
    public function fetch()
    {
        $append_brackets = isset($this->attribs['multiple'])
                        && $this->attribs['multiple']
                        && isset($this->attribs['name'])
                        && substr($this->attribs['name'], -2) != '[]';
        
        // if this is a multiple select, the name needs to end in "[]"
        if ($append_brackets) {
            $this->attribs['name'] .= '[]';
        }
        
        $attr = $this->attribs($this->attribs);
        $this->html = $this->indent(0, "<select {$attr}>");
        
        foreach ($this->stack as $info) {
            $method = array_shift($info);
            $this->$method($info);
        }
        
        if ($this->optgroup) {
            $this->endOptgroup();
        }
        
        $this->html .= $this->indent(0, '</select>');
        return $this->html;
    }
    
    /**
     * 
     * Builds the HTML for a single option.
     * 
     * @param array $info The option info.
     * 
     * @return string
     * 
     */
    protected function buildOption($info)
    {
        list($value, $label, $attribs) = $info;
        
        // set the option value into the attribs
        $attribs['value'] = $value;
        
        // is the value selected? use strict checking to avoid confusion
        // between 0/'0'/false/null/''.
        if (in_array($value, $this->selected, true)) {
            $attribs['selected'] = 'selected';
        } else {
            $attribs['selected'] = null;
        }
        
        // build attributes and return option tag with label text
        $attr = $this->attribs($attribs);
        $this->html .= $this->indent($this->optlevel, "<option {$attr}>$label</option>");
    }
    
    /**
     * 
     * Builds the HTML to begin an optgroup.
     * 
     * @param array $info The optgroup info.
     * 
     * @return void
     * 
     */
    protected function beginOptgroup($info)
    {
        list($label, $attribs) = $info;
        $attribs['label'] = $label;
        $attr = $this->attribs($attribs);
        $this->html .= $this->indent(1, "<optgroup {$attr}>");
        $this->optlevel += 1;
    }
    
    /**
     * 
     * Builds the HTML to end an optgroup.
     * 
     * @return void
     * 
     */
    protected function endOptgroup()
    {
        $this->html .= $this->indent(1, "</optgroup>");
        $this->optlevel -= 1;
    }
}
