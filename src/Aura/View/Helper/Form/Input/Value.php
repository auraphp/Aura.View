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

class Value extends Generic
{
    protected function exec()
    {
        // only set value if not null
        if ($this->value !== null) {
            $this->attribs['value'] = (string) $this->value;
        }
        
        // build and return html
        return parent::exec();
    }    
}
