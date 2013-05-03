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
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelper;
use Aura\View\Helper\Form\Input\Checked;

/**
 * 
 * Helper for series of `<input type="radio">` tags.
 * 
 * @package Aura.View
 * 
 */
class Radios extends AbstractHelper
{
    /**
     * 
     * Constructor.
     * 
     * @param Checked $input A checked-input helper for generating buttons.
     * 
     */
    public function __construct(Checked $input)
    {
        $this->input = $input;
    }
    
    /**
     * 
     * Returns mulitple radio input fields.
     * 
     * @param array $attribs The base attributes for the radios.
     * 
     * @param array $options The radio values and labels.
     * 
     * @param bool $checked Which radio value should be checked.
     * 
     * @param string $separator The separator string to use between each
     * radio.
     * 
     * @return string
     * 
     */
    public function __invoke(
        $attribs,
        $options,
        $checked = null,
        $separator = null
    ) {
        $input = $this->input;
        $attribs['type'] = 'radio';
        $html = '';
        foreach ($options as $value => $label) {
            $attribs['value'] = $value;
            $attribs['label'] = $label;
            $html .= $input($attribs, $checked) . $separator;
        }
        return $html;
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
        
        $options = isset($spec['options'])
                 ? $spec['options']
                 : [];
        
        $value = isset($spec['value'])
               ? $spec['value']
               : null;
               
        if (isset($spec['name'])) {
            $attribs['name'] = $spec['name'];
        }
        
        return $this->__invoke($attribs, $options, $value);
    }
}
