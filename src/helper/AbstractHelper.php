<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace aura\view\helper;

/**
 * 
 * Abstract helper base class.
 * 
 * @package aura.view
 * 
 */
abstract class AbstractHelper
{
    /**
     * 
     * The character set to use when escaping.
     * 
     * @var string
     * 
     */
    protected $escape_charset = 'UTF-8';
    
    /**
     * 
     * The quote style to use when escaping.
     * 
     * @var int
     * 
     */
    protected $escape_quotes = ENT_QUOTES;
    
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
     * Sets the character set to use when escaping.
     * 
     * @param string $charset The character set, e.g. 'UTF-8'.
     * 
     * @return void
     * 
     */
    public function setEscapeCharset($charset)
    {
        $this->escape_charset = $charset;
    }
    
    /**
     * 
     * Sets the quote style to use when escaping.
     * 
     * @param int $quotes The quote style constant, e.g. `ENT_QUOTES`.
     * 
     * @return void
     * 
     */
    public function setEscapeQuotes($quotes)
    {
        $this->escape_quotes = $quotes;
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
     * Escapes values intended for output.
     * 
     * @param scalar $value The value to escape.
     * 
     * @return string The escaped value.
     * 
     */
    protected function escape($value)
    {
        return htmlspecialchars(
            $value,
            $this->escape_quotes,
            $this->escape_charset
        );
    }
    
    /**
     * 
     * Converts an associative array to an escaped attribute string.
     * 
     * @param array $attribs From this array, each key-value pair is 
     * converted to an attribute name and value.
     * 
     * @return string The escaped attributes string.
     * 
     */
    protected function attribs(array $attribs)
    {
        $html = '';
        foreach ($attribs as $key => $val) {
            
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
                $html .= ' ' . $this->escape($key);
            } else {
                $html .= ' ' . $this->escape($key)
                       .  '="' . $this->escape($val) . '"';
            }
        }
        
        // done
        return $html;
    }
}
