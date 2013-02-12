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
 * Helper to generate form field elements.
 * 
 * @package Aura.View
 * 
 */
class Field extends AbstractHelper
{
    /**
     * 
     * @var Input $input Input Helper object for creating `<input>` tag
     * 
     */
    protected $input;
    
    /**
     * 
     * @var Radios $radios Radios Helper object
     * 
     */
    protected $radios;
    
    /**
     * 
     * @var Select $select Select Helper object
     * 
     */
    protected $select;
    
    /**
     * 
     * @var Textarea $textarea Textarea Helper object
     * 
     */
    protected $textarea;
    
    /**
     * 
     * Constructor
     * 
     * @param Input $input Input Helper object
     * 
     * @param Radios $radios Radios Helper object
     * 
     * @param Select $select Select Helper object
     * 
     * @param Textarea $textarea Textarea Helper object
     * 
     */
    public function __construct(
        Input    $input,
        Radios   $radios,
        Select   $select,
        Textarea $textarea
    ) {
        $this->input    = $input;
        $this->radios   = $radios;
        $this->select   = $select;
        $this->textarea = $textarea;
    }
    
    /**
     * 
     * The $spec must consist of five elements:
     * 
     * 'type' (string): The field type.
     * 
     * 'name' (string): The field name.
     * 
     * 'attribs' (array): An array of attributes.
     * 
     * 'options' (array): An array of options (typically for radios and
     * select).
     * 
     * 'value' (mixed): The current value for the field.
     * 
     */
    public function __invoke($spec)
    {
        $type    = $spec['type'];
        $name    = $spec['name'];
        $value   = $spec['value'];
        $attribs = $spec['attribs'];
        $options = $spec['options'];

        switch (strtolower($type)) {
            case 'checkbox':
                return $this->checkbox($name, $attribs, $options, $value);
                break;
            case 'radios':
                return $this->radios($name, $attribs, $options, $value);
                break;
            case 'select':
                return $this->select($name, $attribs, $options, $value);
                break;
            case 'textarea':
                return $this->textarea($name, $attribs, $value);
            default:
                return $this->input($type, $name, $attribs, $value);
                break;
        }
    }
    
    protected function checkbox($name, $attribs, $options, $value)
    {
        foreach ($options as $checked_value => $label) {
            break;
        }
        $attribs['type'] = 'checkbox';
        $attribs['name'] = $name;
        $attribs['value'] = $checked_value;
        $input = $this->input;
        return $input($attribs, $value, $label);
    }
    
    /**
     * 
     * Create an input field via Input object
     * 
     * @param string $type 
     * 
     * @param string $name
     * 
     * @param array $attribs
     * 
     * @param string $value
     * 
     * @return string
     * 
     */
    protected function input($type, $name, $attribs, $value)
    {
        $attribs['type'] = $type;
        $attribs['name'] = $name;
        $input = $this->input;
        return $input($attribs, $value);
    }
    
    /**
     * 
     * Create radio field
     * 
     * @param string $name
     * 
     * @param array $attribs
     * 
     * @param array $options
     * 
     * @param bool $checked
     * 
     * @return string
     * 
     */
    protected function radios($name, $attribs, $options, $checked)
    {
        $attribs['type'] = 'radio';
        $attribs['name'] = $name;
        $radios = $this->radios;
        return $radios($attribs, $options, $checked);
    }
    
    /**
     * 
     * Create select field
     * 
     * @param string $name
     * 
     * @param array $attribs
     * 
     * @param array $options
     * 
     * @param bool $selected
     * 
     * @return string
     * 
     */
    protected function select($name, $attribs, $options, $selected)
    {
        // set the overall attributes
        $attribs['name'] = $name;
        $select = $this->select;
        $select($attribs);
        
        // set the options and optgroups
        foreach ($options as $key => $val) {
            
            $trav = is_array($val) || $val instanceof \Traversable;
            if ($trav) {
                // the key is an optgroup label
                $select->optgroup($key);
                // the values are an array of values and labels
                foreach ($val as $subkey => $subval) {
                    $select->option($subkey, $subval);
                }
            } else {
                // the key is an option value and the val is an option label
                $select->option($key, $val);
            }
        }
        
        // set the selected value
        $select->selected($selected);
        
        // return the html
        return $select->fetch();
    }
    
    /**
     * 
     * Create textarea field
     * 
     * @param string $name
     * 
     * @param array $attribs
     * 
     * @param string $value
     * 
     * @return string
     * 
     */ 
    protected function textarea($name, $attribs, $value)
    {
        $attribs['name'] = $name;
        $textarea = $this->textarea;
        return $textarea($attribs, $value);
    }
}
