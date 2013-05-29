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
 * An HTML select element.
 * 
 * @package Aura.Html
 * 
 */
class Select extends AbstractElement
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
     * Are we currently processing an optgroup?
     * 
     * @var bool
     * 
     */
    protected $optgroup = false;
    
    /**
     * 
     * The current option indent level.
     * 
     * @var int
     * 
     */
    protected $optlevel = 1;
    
    public function __invoke(array $spec = [])
    {
        // reset the stack
        $this->stack = [];
        
        // if there's no spec, return $this so we can build manually
        if (! $spec) {
            return $this;
        }
        
        // otherwise, build and return the html right now
        return parent::__invoke($spec);
    }
    
    /**
     * 
     * Returns the HTML for the element.
     * 
     * @return string
     * 
     */
    protected function html()
    {
        return $this->exec();
    }
    
    /**
     * 
     * Sets the HTML attributes for the select tag.
     * 
     * @return string
     * 
     */
    public function attribs(array $attribs)
    {
        $this->attribs = $attribs;
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
    public function option($value, $label, array $attribs = [])
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
    public function options(array $options, array $attribs = [])
    {
        // set the options and optgroups
        foreach ($options as $key => $val) {
            if (is_array($val)) {
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
    public function optgroup($label, array $attribs = [])
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
        $this->value = (array) $selected;
        return $this;
    }
    
    /**
     * 
     * Returns a select tag with options.
     * 
     * @return mixed The generated HTML if $options were passed, or this
     * select object if not.
     * 
     */
    public function exec()
    {
        // reset tracking properties
        $this->optgroup = false;
        $this->optlevel = 1;
        
        // handle a pseudo-attribute 'placeholder' for the select
        if (isset($this->attribs['placeholder'])) {
            $this->option('', $this->attribs['placeholder']);
            unset($this->attribs['placeholder']);
        }
        
        $append_brackets = isset($this->attribs['multiple'])
                        && $this->attribs['multiple']
                        && isset($this->attribs['name'])
                        && substr($this->attribs['name'], -2) != '[]';
        
        // if this is a multiple select, the name needs to end in "[]"
        if ($append_brackets) {
            $this->attribs['name'] .= '[]';
        }
        
        // open the select
        $attribs = $this->attribs($this->attribs);
        $html = $this->indent(0, "<select {$attribs}>");
        
        // build the options
        foreach ($this->stack as $info) {
            $method = array_shift($info);
            $html .= $this->$method($info);
        }
        
        // close any optgroup tags
        if ($this->optgroup) {
            $this->endOptgroup();
        }
        
        // close the select
        $html .= $this->indent(0, '</select>');
        
        // done!
        return $html;
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
        if (in_array($value, $this->value, true)) {
            $attribs['selected'] = 'selected';
        } else {
            unset($attribs['selected']);
        }
        
        // build attributes and return option tag with label text
        $attribs = $this->attribs($attribs);
        return $this->indent($this->optlevel, "<option {$attribs}>$label</option>");
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
        $this->optlevel += 1;
        $attribs['label'] = $label;
        $attribs = $this->attribs($attribs);
        return $this->indent(1, "<optgroup {$attribs}>");
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
        $this->optlevel -= 1;
        return $this->indent(1, "</optgroup>");
    }
}
