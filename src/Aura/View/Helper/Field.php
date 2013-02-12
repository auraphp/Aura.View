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
    public function __invoke(array $spec)
    {
        $type  = isset($spec['type'])  ? $spec['type']            : null;
        $name  = isset($spec['name'])  ? $spec['name']            : null;
        $attribs  = isset($spec['attribs'])  ? (array) $spec['attribs'] : [];
        $options  = isset($spec['options'])  ? (array) $spec['options'] : [];
        $value = isset($spec['value']) ? $spec['value']           : null;
        
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
    
    /**
     * 
     * Create a checkbox field via Input object.
     * 
     * @param string $name
     * 
     * @param array $attribs
     * 
     * @param array $options
     * 
     * @param string $value
     * 
     * @return string
     * 
     */
    protected function checkbox($name, array $attribs, array $options, $value)
    {
        // get the value and label for the checkbox from the first option
        reset($options);
        $checked_value = key($options);
        $checked_label = current($options);
        
        // set attributes
        $attribs['type'] = 'checkbox';
        $attribs['name'] = $name;
        $attribs['value'] = $checked_value;
        
        // return html
        $input = $this->input;
        return $input($attribs, $value, $checked_label);
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
    protected function input($type, $name, array $attribs, $value)
    {
        $attribs['type'] = $type;
        $attribs['name'] = $name;
        $input = $this->input;
        return $input($attribs, $value);
    }
    
    /**
     * 
     * Create a series of radio buttons.
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
    protected function radios($name, array $attribs, array $options, $checked)
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
    protected function select($name, array $attribs, array $options, $selected)
    {
        // set the overall attributes
        $attribs['name'] = $name;
        $select = $this->select;
        $select($attribs);
        
        // set the options and optgroups
        foreach ($options as $key => $val) {
            
            if (is_array($val)) {
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
        return $select->get();
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
    protected function textarea($name, array $attribs, $value)
    {
        $attribs['name'] = $name;
        $textarea = $this->textarea;
        return $textarea($attribs, $value);
    }
}
