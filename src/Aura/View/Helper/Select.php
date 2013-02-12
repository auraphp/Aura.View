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
    
    protected $attr = [];
    
    protected $optgroup = false;
    
    protected $selected = [];
    
    protected $html = '';
    
    protected $optlevel = 1;
    
    public function __invoke($attr, $options = [], $selected = null)
    
    {
        $this->stack    = [];
        $this->optgroup = false;
        $this->selected = [];
        $this->html     = '';
        $this->attr     = $attr;
        $this->optlevel = 1;
        
        if ($options) {
            $this->options($options);
            $this->selected($selected);
            return $this->fetch();
        } else {
            return $this;
        }
    }
    
    public function option($value, $label, array $attr = [])
    {
        $this->stack[] = ['buildOption', $value, $label, $attr];
        return $this;
    }
    
    public function options(array $options, array $attr = [])
    {
        foreach ($options as $value => $label) {
            $this->option($value, $label, $attr);
        }
        return $this;
    }
    
    public function optgroup($label, array $attr = [])
    {
        if ($this->optgroup) {
            $this->stack[] = ['endOptgroup'];
        }
        $this->stack[] = ['beginOptgroup', $label, $attr];
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
        $append_brackets = isset($this->attr['multiple'])
                        && $this->attr['multiple']
                        && isset($this->attr['name'])
                        && substr($this->attr['name'], -2) != '[]';
        
        // if this is a multiple select, the name needs to end in "[]"
        if ($append_brackets) {
            $this->attr['name'] .= '[]';
        }
        
        $attr = $this->attr($this->attr);

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
        list($value, $label, $attr) = $info;
        
        // set the option value into the attr
        $attr['value'] = $value;
        
        // is the value selected?
        unset($attr['selected']);
        if (in_array($value, $this->selected)) {
            $attr['selected'] = 'selected';
        }
        
        // build attributes and return option tag with label text
        $attr = $this->attr($attr);
        $this->html .= $this->indent($this->optlevel, "<option {$attr}>$label</option>");
    }
    
    protected function beginOptgroup($info)
    {
        list($label, $attr) = $info;
        $attr['label'] = $label;
        $attr = $this->attr($attr);
        $this->html .= $this->indent(1, "<optgroup {$attr}>");
        $this->optlevel += 1;
    }
    
    protected function endOptgroup()
    {
        $this->html .= $this->indent(1, "</optgroup>");
        $this->optlevel -= 1;
    }
}
