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

use Aura\Html\Locator;

/**
 * 
 * A locator to generate a repeated form element element.
 * 
 * @package Aura.Html
 * 
 */
class RepeatLocator extends Locator
{
    /**
     * 
     * Given a element specification, returns a repeated element input.
     * 
     * @param array $spec The element specification.
     * 
     * @return string
     * 
     */
    public function __invoke(array $spec)
    {
        // make sure we have a type
        if (! $spec['type']) {
            $spec['type'] = 'text';
        }
        
        // get the helper
        $helper = $this->get($spec['type']);
        
        // the eventual html
        $html = '';
        
        // do we have a name?
        $name = null;
        if ($spec['name']) {
            // use it as a base prefix
            $name = $spec['name'];
        }
        
        // do we have an ID?
        $id = null;
        if ($spec['attribs']['id']) {
            $id = $spec['attribs']['id'];
        }
        
        // the eventual html
        $html = '';
        
        // go through all the values;
        // rebuild the spec as we go.
        $values = $spec['value'];
        foreach ($values as $key => $val) {
            
            if ($name) {
                // force a name on the element
                $spec['name'] = $name . "[{$key}]";
            }
            
            if ($id) {
                // force an ID on the element
                $spec['attribs']['id'] = $id . "-{$key}";
            }
            
            // force the value on the element
            $spec['value'] = $val;
            
            // build the element html
            $html .= $helper->getElement($spec);
        }
        
        // done!
        return $html;
    }
}
