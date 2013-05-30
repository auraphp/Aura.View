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

use Aura\Html\Escape;

/**
 * 
 * Abstract helper base class.
 * 
 * @package Aura.Html
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
     * The current indent level.
     * 
     * @var int
     * 
     */
    protected $indent_level = 0;
    
    /**
     * 
     * An escape object.
     * 
     * @var Escape
     * 
     */
    protected $escape;
    
    /**
     * 
     * Sets the escape object.
     * 
     * @param Escape $escape The escape object.
     * 
     * @return void
     * 
     */
    public function setEscape(Escape $escape)
    {
        $this->escape = $escape;
    }
    
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
     * Sets the indent level.
     * 
     * @param int $indent_level The indent level.
     * 
     * @return self
     * 
     */
    public function setIndentLevel($indent_level)
    {
        $this->indent_level = (int) $indent_level;
        return $this;
    }

    /**
     * 
     * Converts an associative array to an attribute string.
     * 
     * @param array $attr From this array, each key-value pair
     * is converted to an attribute name and value.
     * 
     * @param array $skip Skip attributes listed in this array.
     * 
     * @return string The attribute string.
     * 
     * @todo Move this to the Escape object entirely.
     * 
     */
    protected function attr(array $attr)
    {
        // pre-empt processing
        if (! $attr) {
            return '';
        }

        $html = '';
        foreach ($attr as $key => $val) {

            // do not add null and false values to the html
            if ($val === null || $val === false) {
                continue;
            }
            
            // get rid of extra spaces in the key
            $key = trim($key);
            
            // space-separate multiple values
            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            
            // what kind of attribute representation?
            if ($val === true) {
                // minimized
                $html .= $this->escape->attr($key);
            } else {
                // full
                $html .= $this->escape->attr($key) . '="'
                       . $this->escape->html($val) . '"';
            }
            
            // space separator
            $html .= ' ';
        }

        // done; remove the last space
        return rtrim($html);
    }
    
    /**
     * 
     * Returns a "void" tag (i.e., one with no body and no closing tag).
     * 
     * @param string $tag The tag name.
     * 
     * @param array $attr The attributes for the tag.
     * 
     * @return string
     * 
     */
    protected function void($tag, array $attr = [])
    {
        $attr = $this->attr($attr);
        $html = "<{$tag} {$attr} />";
        return $html;
    }
    
    /**
     * 
     * Returns an indented string.
     * 
     * @param int $level Indent to this level past the current level.
     * 
     * @param string $text The string to indent.
     * 
     * @return string The indented string.
     * 
     */
    protected function indent($level, $text)
    {
        $level += $this->indent_level;
        return str_repeat($this->indent, $level) . $text . PHP_EOL;
    }
}
