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

use Aura\View\HelperLocator;

/**
 * 
 * A locator to create input field objects.
 * 
 * @package Aura.View
 * 
 */
class Input extends HelperLocator
{
    /**
     * 
     * Invokes an input helper to return field HTML.
     * 
     * @param array $attribs The input tag attributes.
     * 
     * @param mixes $value The current value of the input.
     * 
     * @return string
     * 
     */
    public function __invoke($attribs, $value = null)
    {
        if (! isset($attribs['type']) || ! $attribs['type']) {
            $attribs['type'] = 'text';
        }
        $helper = $this->get($attribs['type']);
        return $helper($attribs, $value);
    }
}
