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
    
    public function __invoke($attr, $options = [], $selected = null)
    {
        $this->stack    = [];
        $this->optgroup = false;
        $this->selected = [];
        $this->html     = '';
        $this->attr  = $attr;
        
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
        $this->selected = (array) $selected;
        return $this;
    }
    
    public function fetch()
    {
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
        $level = ($this->optgroup) ? 2 : 1;
        $this->html .= $this->indent($level, "<option {$attr}>$label</option>");
    }
    
    protected function beginOptgroup($info)
    {
        list($label, $attr) = $info;
        $attr['label'] = $label;
        $attr = $this->attr($attr);
        $this->html .= $this->indent(1, "<optgroup {$attr}>");
    }
    
    protected function endOptgroup()
    {
        $this->html .= $this->indent(1, "</optgroup>");
    }
}
