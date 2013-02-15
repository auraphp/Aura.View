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

/**
 * 
 * Helper for a `<textarea>` tag.
 * 
 * @package Aura.View
 * 
 */
class Textarea extends AbstractHelper
{
    /**
     * 
     * Returns a `<textarea>`.
     * 
     * @param array $attribs Attributes for the textarea tag.
     * 
     * @param mixed $value The contents of the textarea tag.
     * 
     * @return string
     * 
     */
    public function __invoke(
        array $attribs,
        $value = null
    ) {
        $attribs = $this->attribs($attribs);
        return "<textarea {$attribs}>$value</textarea>";
    }
    
    public function getField(array $spec)
    {
        $attribs = isset($spec['attribs'])
                 ? $spec['attribs']
                 : [];
        
        $value = isset($spec['value'])
               ? $spec['value']
               : null;
               
        if (isset($spec['name'])) {
            $attribs['name'] = $spec['name'];
        }
        
        return $this->__invoke($attribs, $value);
    }
}
