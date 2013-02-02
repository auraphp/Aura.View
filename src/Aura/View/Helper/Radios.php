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

/**
 * 
 * Helper for series of `<input type="radio">` tags.
 * 
 * @package Aura.View
 * 
 */
class Radios extends AbstractHelper
{
    public function __construct(Input $input)
    {
        $this->input = $input;
    }
    
    public function __invoke(
        array $attr,
        array $options,
        $checked = null,
        $separator = PHP_EOL
    ) {
        $input = $this->input;
        $attr['type'] = 'radio';
        $html = '';
        foreach ($options as $value => $label) {
            $attr['value'] = $value;
            $html .= $input($attr, $checked, $label) . $separator;
        }
        return $html;
    }
}
