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
    public function __construct(Checked $input)
    {
        $this->input = $input;
    }
    
    /**
     * 
     * Return mulitple radio fields `<input type="radio" />`
     * 
     * @param array $attribs
     * 
     * @param array $options
     * 
     * @param bool $checked Default to null,
     * 
     * @param string $separator Defaults to PHP_EOL
     * 
     * @return string
     * 
     */
    public function __invoke(
        array $attribs,
        array $options,
        $checked = null,
        $separator = PHP_EOL
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
