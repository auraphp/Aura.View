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
namespace Aura\Html\Helper\Input;

/**
 * 
 * An HTML radio input.
 * 
 * @package Aura.Html
 * 
 */
class Radio extends AbstractChecked
{
    /**
     * 
     * Returns the HTML for the input.
     * 
     * @return string
     * 
     */
    protected function html()
    {
        $this->attribs['type'] = 'radio';
        $input = $this->htmlChecked();
        $html  = $this->htmlLabel($input);
        return $this->indent(0, $html);
    }
}
