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

use Aura\View\Helper\Input\Locator;

class Input
{
    protected $input_locator;
    
    public function __construct(Locator $input_locator)
    {
        $this->input_locator = $input_locator;
    }
    
    public function __invoke(
        $attribs,
        $value = null,
        $label = null,
        $label_attribs = []
    ) {
        $type = $attribs['type'];
        $input = $this->input_locator->get($type);
        return $input($attribs, $value, $label, $label_attribs);
    }
}
