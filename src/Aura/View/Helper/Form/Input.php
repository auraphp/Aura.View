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

class Input extends HelperLocator
{
    public function __invoke(
        $attribs,
        $value = null,
        $label = null,
        $label_attribs = []
    ) {
        if (! isset($attribs['type'])) {
            $attribs['type'] = 'text';
        }
        $helper = $this->get($attribs['type']);
        return $helper($attribs, $value, $label, $label_attribs);
    }
}
