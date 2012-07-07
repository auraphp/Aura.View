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
namespace Aura\View\Escaper;

use Aura\View\EscaperFactory;

/**
 * 
 * Object
 * 
 * @package Aura.View
 * 
 */
class Object implements \ArrayAccess
{
    /**
     * 
     * A factory to create Escaper
     *
     * @var EscaperFactory
     * 
     */
    protected $factory;
    
    protected $object;
    
    /**
     *
     * flags in PHP, Default is ENT_QUOTES
     * 
     * @var int
     * 
     */
    protected $quotes;
    
    /**
     *
     * Character-set, Default to UTF-8
     * 
     * @var string
     * 
     */
    protected $charset;
    
    // FIXME constructor
    /**
     * 
     * Constructor
     *
     * @param EscaperFactory $factory
     * 
     * @param type $object
     * 
     * @param type $quotes
     * 
     * @param string $charset 
     * 
     */
    public function __construct(EscaperFactory $factory, $object, $quotes, $charset)
    {
        $this->factory = $factory;
        $this->object  = $object;
        $this->quotes  = $quotes;
        $this->charset = $charset;
    }
    
    /**
     *
     * Magic get
     * 
     * @param string $prop
     * 
     * @return type 
     * 
     */
    public function __get($prop)
    {
        return $this->__escape($this->object->$prop);
    }
    
    /**
     *
     * Magic set
     * 
     * @param type $prop
     * 
     * @param type $spec 
     * 
     */
    public function __set($prop, $spec)
    {
        $this->object->$prop = $spec;
    }
    
    /**
     *
     * Magic isset
     * 
     * @param type $prop
     * 
     * @return type 
     * 
     */
    public function __isset($prop)
    {
        return isset($this->object->$prop);
    }
    
    /**
     *
     * Magic unset
     * 
     * @param type $prop 
     * 
     */
    public function __unset($prop)
    {
        unset($this->object->$prop);
    }
    
    // FIXME , docblocks
    /**
     *
     * Callback
     * 
     * @param type $method
     * 
     * @param type $params 
     * 
     */
    public function __call($method, $params)
    {
        return $this->__escape(call_user_func_array(
            [$this->object, $method],
            $params
        ));
    }
    
    // FIXME
    /**
     *
     * Raw data
     * 
     * @return type 
     * 
     */
    public function __raw()
    {
        return $this->object;
    }
    
    // FIXME
    /**
     * 
     * Escape
     *
     * @param type $spec
     * 
     * @return type 
     * 
     */
    public function __escape($spec)
    {
        if (is_string($spec)) {
            // escape all actual strings, but do not double-escape
            return htmlspecialchars($spec, $this->quotes, $this->charset, false);
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
     * ArrayAccess: sets a property value.
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
        $this->object->$prop = $spec;
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
        unset($this->object->$prop);
    }
}
