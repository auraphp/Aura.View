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
    
    public function __invoke($attribs, $options = [], $selected = null)
    {
        $this->stack    = [];
        $this->optgroup = false;
        $this->selected = [];
        $this->html     = '';
        $this->attribs  = $attribs;
        $this->optlevel = 1;
        
        if ($options) {
            $this->options($options);
            $this->selected($selected);
            return $this->fetch();
        } else {
            return $this;
        }
    }
    
    public function option($value, $label, array $attribs = [])
    {
        $this->stack[] = ['buildOption', $value, $label, $attribs];
        return $this;
    }
    
    public function options(array $options, array $attribs = [])
    {
        foreach ($options as $value => $label) {
            $this->option($value, $label, $attribs);
        }
        return $this;
    }
    
    public function optgroup($label, array $attribs = [])
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
        $this->selected = (array) $selected;
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
        
        // is the value selected?
        unset($attribs['selected']);
        if (in_array($value, $this->selected)) {
            $attribs['selected'] = 'selected';
        }
        
        // build attributes and return option tag with label text
        $attr = $this->attribs($attribs);
        $level = $this->optlevel;
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
