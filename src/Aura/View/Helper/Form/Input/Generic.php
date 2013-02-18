<?php
namespace Aura\View\Helper\Form\Input;

use Aura\View\Helper\AbstractHelper;

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
    
    protected function exec()
    {
        return $this->indent(0, $this->void('input', $this->attribs));
    }
    
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
}
