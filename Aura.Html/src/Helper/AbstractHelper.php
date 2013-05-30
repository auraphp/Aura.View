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
                $html .= $this->escapeAttr($key) . '="'
                       . $this->escapeHtml($val) . '"';
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
    /**
     * 
     * HTML entities mapped from ord() values for attribute characters.
     * 
     * @var array
     * 
     */
    protected $attr_char_entities = array(
        34 => '&quot;',
        38 => '&amp;',
        60 => '&lt;',
        62 => '&gt;',
    );

    /**
     * 
     * Escapes a string for HTML content or quoted attribute context.
     * 
     * @param string $raw The raw string.
     * 
     * @return string The escaped string.
     * 
     */
    protected function escapeHtml($raw)
    {
        return htmlspecialchars(
            $raw,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8',
            false // do not double-encode
        );
    }
    
    /**
     * 
     * Escapes strings for an unquoted attribute context.
     * 
     * @param mixed $raw The raw value.
     * 
     * @return mixed The escaped value.
     * 
     */
    public function escapeAttr($raw)
    {
        // is the raw value composed only of safe characters?
        if (preg_match("/^[a-z0-9,._-]*$/iDSu", $raw)) {
            // yes, no need to escape further
            return $raw;
        }
        
        // replace unsafe chars in the raw value
        return preg_replace_callback(
            "/[^$this->safe]/iDSu",
            [$this, 'escapeAttrChar'],
            $raw
        );
    }
    
    /**
     * 
     * Callback to replace unsafe characters with escaped ones.
     * 
     * @param array $matches Characters matched from preg_replace_callback().
     * 
     * @return string Escaped characters.
     * 
     */
    protected function escapeAttrChar(array $matches)
    {
        // get the character and its ord() value
        $char = $matches[0];
        $ord = ord($char);
        
        // handle chars undefined in HTML
        $undef = ($ord <= 0x1f && $char != "\t" && $char != "\n" && $char != "\r")
              || ($ord >= 0x7f && $ord <= 0x9f);
        
        if ($undef) {
            // use the Unicode replacement char
            return '&#xFFFD;';
        }
        
        // is this a UTF-16BE character?
        if (strlen($char) > 1) {
            // convert it
            $char = $this->convertUTF16BEtoUTF8($char);
        }
        
        // retain the ord value
        $ord = hexdec(bin2hex($char));
        
        // is this a mapped entity?
        if (isset($this->attr_char_entities[$ord])) {
            return $this->attr_char_entities[$ord];
        }

        // is this an upper-range hex entity?
        if ($ord > 255) {
            return sprintf('&#x%04X;', $ord);
        }
        
        // everything else
        return sprintf('&#x%02X;', $ord);
    }
    
    /**
     * 
     * Converts a single character from UTF-16BE to UTF-8; used by extending
     * escaper classes.
     * 
     * @param string $char The UTF-16BE character.
     * 
     * @return string The converted UTF-8 character.
     * 
     */
    protected function convertUTF16BEtoUTF8($char)
    {
        $to = 'UTF-16BE';
        $from = 'UTF-8';
        
        if (function_exists('iconv')) {
            return (string) iconv($from, $to, $char);
        }
        
        if (function_exists('mb_convert_encoding')) {
            return (string) mb_convert_encoding($char, $to, $from);
        }
        
        throw new Exception\ExtensionNotInstalled("Extension 'iconv' or 'mbstring' is required.");
    }
}
