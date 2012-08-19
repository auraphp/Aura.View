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
 * Helper to create a form of type text
 * 
 * @package Aura.View
 * 
 */
class Formtext extends FormElement
{
    /**
     * 
     * Generates a 'text' element.
     * 
     * @param array $info An array of element information.
     * 
     * @return string The element XHTML.
     * 
     */
    public function __invoke($info)
    {
        $this->prepare($info);
        return '<input type="text"'
             . ' name="' . $this->name . '"'
             . ' value="' . $this->value . '"'
             . $this->attribs($this->attribs)
             . ' />';
    }
}
 