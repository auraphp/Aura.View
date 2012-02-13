<?php
namespace Aura\View;
class Escaper implements IteratorAggregate
{
    protected $object;
    
    protected $quotes;
    
    protected $charset;
    
    public function __construct($object, $quotes = ENT_QUOTES, $charset = 'UTF-8')
    {
        $this->object  = (object) $object;
        $this->quotes  = $quotes;
        $this->charset = $charset;
    }
    
    public function __get($key)
    {
        // get the underlying object property and escape it
        return $this->___escape($this->object->$key);
    }
    
    public function __call($method, $params)
    {
        // call the underlying object method and escape its return value
        return $this->___escape(call_user_func_array(
            [$this->object, $method],
            $params
        ));
    }
    
    // you're not going to be able to tell the underlying object type
    protected function ___escape($val)
    {
        if (is_string($val)) {
            // escape all actual strings
            return htmlspecialchars($val, $this->quotes, $this->charset);
        } elseif (is_object($val) || is_array($val)) {
            // decorate all objects and arrays with an escaper.
            // wil this allow, e.g., $val[0] ?
            return new Escaper($val);
        } else {
            // don't escape numerics, resources, bools, nulls, etc.
            // this is to soothe things like Iterator and IteratorAggregate
            // and to make sure we can use true/false/null.
            return $val;
        }
    }
    
    public function getItert
}
