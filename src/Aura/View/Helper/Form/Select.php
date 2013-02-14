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
    protected $stack = [];
    
    protected $attribs = [];
    
    protected $optgroup = false;
    
    protected $selected = [];
    
    protected $html = '';
    
    protected $optlevel = 1;
    
    public function __invoke($attribs, $options = [], $value = null)
    {
        $this->stack    = [];
        $this->optgroup = false;
        $this->selected = [];
        $this->html     = '';
        $this->attribs  = $attribs;
        $this->optlevel = 1;
        
        if (isset($this->attribs['placeholder'])) {
            $this->option(
                '',
                $this->attribs['placeholder']
            );
            unset($this->attribs['placeholder']);
        }
        
        if ($options) {
            $this->options($options);
            $this->selected($value);
            return $this->fetch();
        } else {
            return $this;
        }
    }
    
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
    
    public function option($value, $label, $attribs = [])
    {
        $this->stack[] = ['buildOption', $value, $label, $attribs];
        return $this;
    }
    
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
    
    public function optgroup($label, $attribs = [])
    {
        if ($this->optgroup) {
            $this->stack[] = ['endOptgroup'];
        }
        $this->stack[] = ['beginOptgroup', $label, $attribs];
        $this->optgroup = true;
        return $this;
    }
    
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
    
    protected function beginOptgroup($info)
    {
        list($label, $attribs) = $info;
        $attribs['label'] = $label;
        $attr = $this->attribs($attribs);
        $this->html .= $this->indent(1, "<optgroup {$attr}>");
        $this->optlevel += 1;
    }
    
    protected function endOptgroup()
    {
        $this->html .= $this->indent(1, "</optgroup>");
        $this->optlevel -= 1;
    }
}
