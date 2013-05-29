<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.Html
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Html\Helper\Form;

/**
 * 
 * An HTML radio element.
 * 
 * @package Aura.Html
 * 
 */
class Radio extends AbstractChecked
{
    /**
     * 
     * Returns the HTML for the element.
     * 
     * @return string
     * 
     */
    protected function html()
    {
        $input = $this->htmlInput();
        $html  = $this->htmlLabel($input);
        return $this->indent(0, $html);
    }
}
