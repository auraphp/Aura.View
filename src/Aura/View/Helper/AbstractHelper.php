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
     * The base indent level.
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
     * @return self
     * 
     */
    public function setIndent($indent)
    {
        $this->indent = $indent;
        return $this;
    }
    
    /**
     * 
     * Sets the base indent level.
     * 
     * @param int $indent_level The base indent level.
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
     */
    protected function attr(array $attr, array $skip = [])
    {
        // pre-empt processing
        if (! $attr) {
            return '';
        }

        $html = [];
        foreach ($attr as $key => $val) {

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
    
    /**
     * 
     * Returns a "void" tag; that is, a <tag /> with no content.
     * 
     * @param string $tag The tag name.
     * 
     * @param array $attr Attributes for the tag.
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
     * Returns whitespace-indented text.
     * 
     * @param int $level The indent level; this is added to the base indent
     * level.
     * 
     * @param string $text The text to indent.
     * 
     * @return string
     * 
     */
    protected function indent($level, $text)
    {
        $level += $this->indent_level;
        return str_repeat($this->indent, $level) . $text . PHP_EOL;
    }
}
