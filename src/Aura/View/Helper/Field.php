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
     * 'attr' (array): An array of attributes.
     * 
     * 'opts' (array): An array of opts (typically for radios and
     * select).
     * 
     * 'value' (mixed): The current value for the field.
     * 
     */
    public function __invoke(array $spec)
    {
        $type  = isset($spec['type'])  ? $spec['type']            : null;
        $name  = isset($spec['name'])  ? $spec['name']            : null;
        $attr  = isset($spec['attr'])  ? (array) $spec['attr'] : [];
        $opts  = isset($spec['opts'])  ? (array) $spec['opts'] : [];
        $value = isset($spec['value']) ? $spec['value']           : null;
        
        switch (strtolower($type)) {
            case 'checkbox':
                return $this->checkbox($name, $attr, $opts, $value);
                break;
            case 'radios':
                return $this->radios($name, $attr, $opts, $value);
                break;
            case 'select':
                return $this->select($name, $attr, $opts, $value);
                break;
            case 'textarea':
                return $this->textarea($name, $attr, $value);
            default:
                return $this->input($type, $name, $attr, $value);
                break;
        }
    }
    
    /**
     * 
     * Create a checkbox field via Input object.
     * 
     * @param string $name
     * 
     * @param array $attr
     * 
     * @param array $opts
     * 
     * @param string $value
     * 
     * @return string
     * 
     */
    protected function checkbox($name, array $attr, array $opts, $value)
    {
        // get the value and label for the checkbox from the first option
        reset($opts);
        $checked_value = key($opts);
        $checked_label = current($opts);
        
        // set attributes
        $attr['type'] = 'checkbox';
        $attr['name'] = $name;
        $attr['value'] = $checked_value;
        
        // return html
        $input = $this->input;
        return $input($attr, $value, $checked_label);
    }
    
    /**
     * 
     * Create an input field via Input object
     * 
     * @param string $type 
     * 
     * @param string $name
     * 
     * @param array $attr
     * 
     * @param string $value
     * 
     * @return string
     * 
     */
    protected function input($type, $name, array $attr, $value)
    {
        $attr['type'] = $type;
        $attr['name'] = $name;
        $input = $this->input;
        return $input($attr, $value);
    }
    
    /**
     * 
     * Create a series of radio buttons.
     * 
     * @param string $name
     * 
     * @param array $attr
     * 
     * @param array $opts
     * 
     * @param bool $checked
     * 
     * @return string
     * 
     */
    protected function radios($name, array $attr, array $opts, $checked)
    {
        $attr['type'] = 'radio';
        $attr['name'] = $name;
        $radios = $this->radios;
        return $radios($attr, $opts, $checked);
    }
    
    /**
     * 
     * Create select field
     * 
     * @param string $name
     * 
     * @param array $attr
     * 
     * @param array $opts
     * 
     * @param bool $selected
     * 
     * @return string
     * 
     */
    protected function select($name, array $attr, array $opts, $selected)
    {
        // set the overall attributes
        $attr['name'] = $name;
        $select = $this->select;
        $select($attr);
        
        // set the options and optgroups
        foreach ($opts as $key => $val) {
            
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
     * @param array $attr
     * 
     * @param string $value
     * 
     * @return string
     * 
     */ 
    protected function textarea($name, array $attr, $value)
    {
        $attr['name'] = $name;
        $textarea = $this->textarea;
        return $textarea($attr, $value);
    }
}
