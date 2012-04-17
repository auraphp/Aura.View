<?php
namespace Aura\View\Escaper;

use Aura\View\EscaperFactory;
use Aura\View\Exception\Immutable as ImmutableException;

class Object implements \ArrayAccess
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
    
    public function __get($prop)
    {
        return $this->__escape($this->object->$prop);
    }
    
    public function __set($prop, $spec)
    {
        throw new ImmutableException;
    }
    
    public function __call($method, $params)
    {
        return $this->__escape(call_user_func_array(
            [$this->object, $method],
            $params
        ));
    }
    
    public function __raw()
    {
        return $this->object;
    }
    
    public function __escape($spec)
    {
        if (is_string($spec)) {
            // escape all actual strings
            return htmlspecialchars($spec, $this->quotes, $this->charset);
        } elseif (is_array($spec) || is_object($spec)) {
            // wrap objects and arrays in escaper
            return $this->factory->newInstance($spec);
        } else {
            // don't escape numerics, resources, bools, nulls, resources, etc.
            return $spec;
        }
    }
    
    /**
     * 
     * ArrayAccess: does the requested property exist?
     * 
     * @param string $prop The requested property.
     * 
     * @return bool
     * 
     */
    public function offsetExists($prop)
    {
        return isset($this->object->$prop);
    }
    
    /**
     * 
     * ArrayAccess: get a property value.
     * 
     * @param string $prop The requested property.
     * 
     * @return mixed
     * 
     */
    public function offsetGet($prop)
    {
        return $this->__escape($this->object->$prop);
    }
    
    /**
     * 
     * ArrayAccess: set a property value; throws an exception because Escaper
     * is immutable.
     * 
     * @param string $prop The requested property.
     * 
     * @param mixed $spec The value to set it to.
     * 
     * @return void
     * 
     */
    public function offsetSet($prop, $spec)
    {
        throw new ImmutableException;
    }
    
    /**
     * 
     * ArrayAccess: unset a property.
     * 
     * @param string $prop The requested property.
     * 
     * @return void
     * 
     */
    public function offsetUnset($prop)
    {
        throw new ImmutableException;
    }
}
