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
     * The current indent level.
     * 
     * @var int
     * 
     */
    protected $indent_level = 0;
    
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

            // skip null and false values
            if ($val === null || $val === false) {
                continue;
            }

            // space-separate multiple values
            if (is_array($val)) {
                $val = implode(' ', $val);
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
    
    /**
     * 
     * Returns a "void" tag (i.e., one with no body and no closing tag).
     * 
     * @param string $tag The tag name.
     * 
     * @param array $attribs The attributes for the tag.
     * 
     * @return string
     * 
     */
    protected function void($tag, $attribs)
    {
        $attr = $this->attribs($attribs);
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
