<?php
namespace Aura\View;

use Closure;

class Helper
{
    protected $closures;
    
    // invokes a named helper closure
    public function __call($name, $args)
    {
        return call_user_func_array($this->closures[$name], $args);
    }
    
    // sets a closure as a helper, binding to this helper object
    public function set($name, Closure $closure)
    {
        $closure = $closure->bindTo($this, get_class($this));
        $this->closures[$name] = $closure;
    }
    
    // escapes HTML
    public function safeHtml($raw)
    {
        return htmlspecialchars($raw, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
    
    // strips plain values of unsafe characters, builds an attribute string
    // of stripped names and escaped values from arrays
    public function safeAttr($raw)
    {
        if (! is_array($raw)) {
            // strip unsafe characters
            return preg_replace('/[^a-z0-9,\.\-_]/iSu', '', $raw);
        }
        
        // recursively build quoted attributes
        $attr = '';
        foreach ($raw as $key => $val) {
            
            // do not add null and false values
            if ($val === null || $val === false) {
                continue;
            }
            
            // implode array values to space-separated values
            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            
            // what kind of attribute value?
            if ($val === true) {
                // minimized attribute
                $attr .= $this->safeAttr($key) . ' ';
            } elseif ($val !== false && $val !== null) {
                // full attribute
                $attr .= $this->safeAttr($key) . '="'
                       . $this->safeHtml($val) . '" ';
            }
        }
        
        // trim off the last space, and done
        return rtrim($attr);
    }
}
