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

use Aura\Html\Escaper;

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
     * An escaper object.
     * 
     * @var Escaper
     * 
     */
    protected $escaper;
    
    /**
     * 
     * Sets the escaper object.
     * 
     * @param Escaper $escaper The escaper object.
     * 
     * @return void
     * 
     */
    public function setEscaper(Escaper $escaper)
    {
        $this->escaper = $escaper;
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
     * Keys are attribute names, and values are attribute values. A value
     * of boolean true indicates a minimized attribute; for example,
     * `['disabled' => 'disabled']` results in `disabled="disabled"`, but
     * `['disabled' => true]` results in `disabled`.  Values of `false` or
     * `null` will omit the attribute from output.  Array values will be
     * concatenated and space-separated before escaping.
     * 
     * @param array $attr An array of key-value pairs where the key is the
     * attribute name and the value is the attribute value.
     * 
     * @return string The attribute array converted to a properly-escaped
     * string.
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
            
            // concatenate and space-separate multiple values
            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            
            // what kind of attribute representation?
            if ($val === true) {
                // minimized
                $html .= $this->escaper->attr($key);
            } else {
                // full; because the it is quoted, we can use html ecaping
                $html .= $this->escaper->attr($key) . '="'
                       . $this->escaper->html($val) . '"';
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
