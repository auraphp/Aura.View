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
 * Helper to generate `<a ... />` tags.
 * 
 * @package Aura.View
 * 
 */
abstract class FormElement extends AbstractHelper
{
    /**
     * 
     * Default form element information.
     * 
     * @var array
     * 
     */
    protected $info = array(
        'type'    => '',
        'name'    => '',
        'value'   => '',
        'label'   => '',
        'attribs' => '',
        'options' => array(),
        'require' => false,
        'disable' => false,
        'invalid' => array(),
    );
    
    /**
     * 
     * The order in which to process info keys.
     * 
     * Attribs is last so that attributes are unset properly.
     * 
     * @var array
     * 
     */
    protected $keys = array(
        'type',
        'name',
        'value',
        'label',
        'options',
        'require',
        'disable',
        'invalid',
        'attribs',
    );
    
    /**
     * 
     * The form element type (text, radio, etc).
     * 
     * @var string
     * 
     */
    protected $type;
    
    /**
     * 
     * The form element name.
     * 
     * @var string
     * 
     */
    protected $name;
    
    
    /**
     * 
     * The form element value.
     * 
     * @var string
     * 
     */
    protected $value;
    
    /**
     * 
     * The form element label.
     * 
     * @var string
     * 
     */
    protected $label;
    
    /**
     * 
     * The form element attributes (checked, selected, readonly, etc).
     * 
     * @var array
     * 
     */
    
    protected $attribs;
    
    /**
     * 
     * Options for checkbox, select, and radio elements.
     * 
     * @var array
     * 
     */
    protected $options;
    
    /**
     * 
     * Whether or not the element is required.
     * 
     * @var bool
     * 
     */
    protected $require;
    
    /**
     * 
     * Whether or not the element is to be disabled.
     * 
     * @var bool
     * 
     */
    protected $disable;
    
    /**
     * 
     * Feedback messages for the element.
     * 
     * @var bool
     * 
     */
    protected $invalid;
    
    /**
     * 
     * Prepares an info array and imports to the properties.
     * 
     * @param array $info An array of element information.
     * 
     * @return void
     * 
     */
    protected function prepare($info)
    {
        $info = array_merge($this->info, $info);
        
        settype($info['type'], 'string');
        settype($info['name'], 'string');
        settype($info['label'], 'string');
        settype($info['attribs'], 'array');
        settype($info['options'], 'array');
        settype($info['require'], 'bool');
        settype($info['disable'], 'bool');
        settype($info['invalid'], 'array');
        
        foreach ($this->keys as $key) {
            unset($info['attribs'][$key]);
            $this->$key = $info[$key];
        }
    }
}

