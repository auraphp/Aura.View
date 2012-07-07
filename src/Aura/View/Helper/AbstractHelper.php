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
 * Abstract helper base class.
 * 
 * @package Aura.View
 * 
 */
abstract class AbstractHelper
{
    /**
     * 
     * Use this as one level of indentation for output.
     * 
     * @var string
     * 
     */
    protected $indent = '    ';
    
    /**
     * 
     * Sets the string to use for one level of indentation.
     * 
     * @param string $indent The indent string.
     * 
     * @return void
     * 
     */
    public function setIndent($indent)
    {
        $this->indent = $indent;
    }
    
    /**
     * 
     * Converts an associative array to an attribute string.
     * 
     * @param array|Traversable $attribs From this array, each key-value pair
     * is converted to an attribute name and value.
     * 
     * @param array $skip Skip attributes listed in this array.
     * 
     * @return string The attribute string.
     * 
     */
    protected function attribs($attribs, array $skip = [])
    {
        // pre-empt processing
        if (! $attribs) {
            return '';
        }
        
        $html = [];
        foreach ($attribs as $key => $val) {
            
            // skip this attribute?
            if (in_array($key, $skip)) {
                continue;
            }
            
            // space-separate multiple values
            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            
            // skip empty values; use a string cast and strict equality to 
            // make sure that a string zero is not counted as an empty value.
            if ((string) $val === '') {
                continue;
            }
            
            // add to the attributes
            if ($val === true) {
                $html[] = $key;
            } else {
                $html[] = "{$key}=\"$val\"";
            }
        }
        
        // done
        return implode(' ', $html);
    }
}
