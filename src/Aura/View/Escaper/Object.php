<?php
namespace Aura\View\Escaper;
use Aura\View\EscaperFactory;
class Object
{
    protected $factory;
    
    protected $object;
    
    protected $quotes;
    
    protected $charset;
    
    public function __construct(EscaperFactory $factory, $object, $quotes, $charset)
    {
        $this->factory = $factory;
        $this->object  = $object;
        $this->quotes  = $quotes;
        $this->charset = $charset;
    }
    
    public function __get($key)
    {
        return $this->__escape($this->object->$key);
    }
    
    public function __call($method, $params)
    {
        return $this->__escape(call_user_func_array(
            [$this->object, $method],
            $params
        ));
    }
    
    protected function __escape($val)
    {
        if (is_string($val)) {
            // escape all actual strings
            return htmlspecialchars($val, $this->quotes, $this->charset);
        } elseif (is_array($val) || is_object($val)) {
            // wrap objects and arrays in escaper
            return $this->factory->newInstance($val);
        } else {
            // don't escape numerics, resources, bools, nulls, resources, etc.
            return $val;
        }
    }
    
    protected function __raw()
    {
        return $this->object;
    }
    
    protected function __getType()
    {
        return gettype($this->object);
    }
}
