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
 * A locator to generate form field elements.
 * 
 * @package Aura.View
 * 
 */
class Field extends HelperLocator
{
    /**
     * 
     * Given a field specification, returns an object of the specified type.
     * 
     * @param array $spec The field specification.
     * 
     * @return object
     * 
     */
    public function __invoke($spec)
    {
        if (! isset($spec['type']) || ! $spec['type']) {
            $spec['type'] = 'text';
        }
        $helper = $this->get($spec['type']);
        return $helper->getField($spec);
    }
}
