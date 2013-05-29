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
 * A helper to generate form input elements.
 * 
 * @package Aura.Html
 * 
 */
class Input
{
    /**
     * 
     * A locator with input element objects.
     * 
     * @var Locator
     * 
     */
    protected $locator;
    
    /**
     * 
     * Constructor.
     * 
     * @param Locator $locator A locator with input element objects.
     * 
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }
    
    /**
     * 
     * Given an element specification, returns the HTML for the element.
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
        $input = $this->locator->get($spec['type']);
        return $input($spec);
    }
}
