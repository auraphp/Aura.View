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
namespace Aura\View\Helper\Form\Input;

use Aura\View\Helper\AbstractHelper;

/**
 * 
 * Helper to generate a generic input field.
 * 
 * @package Aura.View
 * 
 */
class Generic extends AbstractHelper
{
    /**
     * 
     * Attributes for the input tag.
     * 
     * @var array
     * 
     */
    protected $attribs = [];
    
    /**
     * 
     * The field value for the input element. This may overwrite the 'value'
     * attribute, or may be used to see if the 'checked' attribute should be
     * set, depending on the input type.
     * 
     * @var mixed
     * 
     */
    protected $value;
    
    /**
     * 
     * Returns an `<input>` tag, optionally wrapped in a `<label>` tag.
     * 
     * @param array $attribs Attributes for the input tag.
     * 
     * @param mixed $value The field value for the input element. This may 
     * overwrite the 'value' attribute, or may be used to see if the 'checked'
     * attribute should be set, depending on the input type.  This may also
     * include a pseudo-attribute 'label' to be used as a label around the
     * input, such as for checkbox or radio inputs.
     * 
     * @return string
     * 
     */
    public function __invoke($attribs, $value = null)
    {
        $this->attribs = $attribs;
        $this->value   = $value;
        return $this->exec();
    }
    
    /**
     * 
     * Returns the input field HTML based on a field specification.
     * 
     * @param array $spec A field specification.
     * 
     * @return string
     * 
     */
    public function getField($spec)
    {
        $attribs = isset($spec['attribs'])
                 ? $spec['attribs']
                 : [];
        
        $value = isset($spec['value'])
               ? $spec['value']
               : null;
        
        /// insert a 'type' attribute
        if (isset($spec['type'])) {
            $attribs['type'] = $spec['type'];
        } else {
            $attribs['type'] = 'text';
        }
        
        // insert a 'name' attribute
        if (isset($spec['name'])) {
            $attribs['name'] = $spec['name'];
        }
        
        // build the html
        return $this->__invoke($attribs, $value);
    }
    
    /**
     * 
     * Returns the input field HTML.
     * 
     * @return string
     * 
     */
    protected function exec()
    {
        return $this->indent(0, $this->void('input', $this->attribs));
    }
}
