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

use Aura\View\Exception\ExtensionNotInstalled;

/**
 * 
 * Helper to escape values for HTML content and quoted attributes.
 * 
 * Serves as a base class for other escapers.
 * 
 * @package Aura.View
 * 
 */
class Escape extends AbstractHelper
{
    /**
     * 
     * A regular expression character class considered "safe" by the escaper.
     * 
     * @var string
     * 
     */
    protected $safe = null;
    
    /**
     * 
     * Escapes strings; recursively escapes array keys and values; non-string
     * non-array values are not escaped.
     * 
     * This means that null, boolean, resource, object, etc. values are not
     * escaped and are returned in their original state.
     * 
     * @param mixed $raw The raw value.
     * 
     * @return mixed The escaped value.
     * 
     */
    public function __invoke($raw)
    {
        // recursively escape array keys and values
        if (is_array($raw)) {
            $esc = [];
            foreach ($raw as $key => $val) {
                $key = $this->__invoke($key);
                $val = $this->__invoke($val);
                $esc[$key] = $val;
            }
            return $esc;
        }
        
        // escape strings
        if (is_string($raw)) {
            return $this->escape($raw);
        }
        
        // skip everything else (null, boolean, resource, object, etc)
        return $raw;
    }
    
    /**
     * 
     * Escapes the raw string.
     * 
     * @param string $raw The raw string.
     * 
     * @return string The escaped string.
     * 
     */
    protected function escape($raw)
    {
        // if there are safe characters ...
        if ($this->safe) {
            if (preg_match("/^[{$this->safe}]*$/iDSu", $raw)) {
                // ... allow only safe chars in raw values
                return $raw;
            } else {
                // ... replace unsafe chars in raw vals
                return preg_replace_callback(
                    "/[^{$this->safe}]/iDSu",
                    [$this, 'replace'],
                    $raw
                );
            }
        }
        
        // there are no safe characters, escape the whole thing
        return htmlspecialchars(
            $raw,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }
    
    /**
     * 
     * Converts a single character from UTF-16BE to UTF-8; used by extending
     * escaper classes.
     * 
     * @param string $chr The UTF-16BE character.
     * 
     * @return string The converted UTF-8 character.
     * 
     */
    protected function convert($chr)
    {
        $to = 'UTF-16BE';
        $from = 'UTF-8';
        
        if (function_exists('iconv')) {
            return (string) iconv($from, $to, $chr);
        }
        
        if (function_exists('mb_convert_encoding')) {
            return (string) mb_convert_encoding($chr, $to, $from);
        }
        
        throw new ExtensionNotInstalled("Extension 'iconv' or 'mbstring' is required.");
    }
}
