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
namespace Aura\Html\Helper;

use Aura\Html\HelperLocator;

/**
 * 
 * A helper to generate form input elements.
 * 
 * @package Aura.Html
 * 
 */
class Input extends AbstractHelper
{
    /**
     * 
     * A locator for input elements.
     * 
     * @var HelperLocator
     * 
     */
    protected $helper_locator;
    
    /**
     * 
     * Sets the locator object.
     * 
     * @param HelperLocator $helper_locator
     * 
     * @return void
     * 
     */
    public function setHelperLocator(HelperLocator $helper_locator)
    {
        $this->helper_locator = $helper_locator;
    }
    
    /**
     * 
     * Given an input specification, returns the HTML for the input.
     * 
     * @param array $spec The element specification.
     * 
     * @return object
     * 
     */
    public function __invoke(array $spec = [])
    {
        if (empty($spec['type'])) {
            $spec['type'] = 'text';
        }
        
        if (empty($spec['attribs']['name'])) {
            $spec['attribs']['name'] = $spec['name'];
        }
        
        $input = $this->helper_locator->get($spec['type']);
        return $input($spec);
    }
}
