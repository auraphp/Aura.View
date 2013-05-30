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
namespace Aura\Html\Escape;

use Aura\Html\Exception;

/**
 * 
 * A base class for escape stragegies.
 * 
 * @package Aura.Html
 * 
 */
abstract class AbstractStrategy
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
    public function exec($raw)
    {
        // recursively escape array keys and values
        if (is_array($raw)) {
            $esc = [];
            foreach ($raw as $key => $val) {
                $key = $this->exec($key);
                $val = $this->exec($val);
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
     * Escapes a raw string.
     * 
     * @param string $raw The raw string.
     * 
     * @return string The escaped string.
     * 
     */
    protected function escape($raw)
    {
        // if there are no safe characters ...
        if (! $this->safe) {
            // ... escape the whole thing as if from preg_replace_callback()
            return $this->replace([$raw]);
        }
        
        // is the raw value composed only of safe characters?
        if (preg_match("/^[{$this->safe}]*$/iDSu", $raw)) {
            // yes, no need to escape further
            return $raw;
        }
        
        // replace unsafe chars in the raw value
        return preg_replace_callback(
            "/[^{$this->safe}]/iDSu",
            [$this, 'replace'],
            $raw
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
        
        throw new Exception\ExtensionNotInstalled("Extension 'iconv' or 'mbstring' is required.");
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
    abstract protected function replace(array $matches);
}
