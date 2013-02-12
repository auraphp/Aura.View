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
    
    /**
     * 
     * Return radio fields `<input type="radio" />`
     * 
     * @param array $attribs
     * 
     * @param array $options
     * 
     * @param bool $checked Default to null,
     * 
     * @param string $separator Defaults to PHP_EOL
     * 
     * @return string
     * 
     */
    public function __invoke(
        array $attribs,
        array $options,
        $checked = null,
        $separator = PHP_EOL
    ) {
        $input = $this->input;
        $attribs['type'] = 'radio';
        $html = '';
        foreach ($options as $value => $label) {
            $attribs['value'] = $value;
            $html .= $input($attribs, $checked, $label) . $separator;
        }
        return $html;
    }
}
