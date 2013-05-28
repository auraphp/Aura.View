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
 * A decorator to return escaped values from objects/arrays/etc.
 * 
 * @package Aura.View
 * 
 */
class Object implements \ArrayAccess
{
    /**
     * 
     * An escaper factory.
     * 
     * @var EscaperFactory
     * 
     */
    protected $factory;

    /**
     * 
     * The object being decorated (wrapped) for escaping.
     * 
     * @var object
     * 
     */
    protected $object;

    /**
     * 
     * The type of quoting to use for htmlspecialchars(), e.g. ENT_QUOTES.
     * 
     * @var int
     * 
     */
    protected $quotes;

    /**
     * 
     * The character set to use for htmlspecialchars(), e.g. 'UTF-8'.
     * 
     * @var string
     * 
     */
    protected $charset;

    /**
     * 
     * Constructor.
     * 
     * @param EscaperFactory $factory A factory for escaper objects.
     * 
     * @param object $object The object to be decorated for escaping.
     * 
     * @param string $quotes The type of quotes for htmlspecialchars().
     * 
     * @param string $charset The character set to use for htmlspecialchars().
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
     * Returns an escaped property from the underlying object.
     * 
     * @param string $prop The underlying object property.
     * 
     * @return mixed
     * 
     */
    public function __get($prop)
    {
        return $this->__escape($this->object->$prop);
    }

    /**
     * 
     * Sets a property on the underlying object.
     * 
     * @param string $prop The underlying object property.
     * 
     * @param mixed $spec Set the property to this value.
     * 
     * @return void
     * 
     */
    public function __set($prop, $spec)
    {
        $this->object->$prop = $spec;
    }

    /**
     * 
     * Is the underlying object property set?
     * 
     * @param string $prop The underlying object property.
     * 
     * @return bool
     * 
     */
    public function __isset($prop)
    {
        return isset($this->object->$prop);
    }

    /**
     * 
     * Unsets the underlying object property.
     * 
     * @param string $prop The underlying object property.
     * 
     * @return void
     * 
     */
    public function __unset($prop)
    {
        unset($this->object->$prop);
    }

    /**
     * 
     * Calls an underlying object method and escapes the return value.
     * 
     * @param string $method The underlying object method.
     * 
     * @param array $params Params to pass to the method.
     * 
     * @return mixed
     * 
     */
    public function __call($method, $params)
    {
        return $this->__escape(
            call_user_func_array(
                [$this->object, $method],
                $params
            )
        );
    }

    /**
     * 
     * Returns the underlying object.
     * 
     * @return object
     * 
     */
    public function __raw()
    {
        return $this->object;
    }

    /**
     * 
     * Returns an escaped value.
     * 
     * @param mixed $raw The value to escape. If a string, uses
     * htmlspecialchars(); if an array, recursively escapes keys and values;
     * if an object, wraps it in an escaper; otherwise, does not escape (e.g.
     * numerics, resources, bools, nulls, etc.).
     * 
     * @return mixed
     * 
     */
    public function __escape($raw)
    {
        if (is_string($raw)) {
            // escape all actual strings, but do not double-escape
            return htmlspecialchars($raw, $this->quotes, $this->charset, false);
        }
        
        if (is_array($raw)) {
            // recursively escape the array
            $esc = [];
            foreach ($raw as $key => $val) {
                $key = $this->__escape($key);
                $val = $this->__escape($val);
                $esc[$key] = $val;
            }
            return $esc;
        }
        
        if (is_object($raw)) {
            // wrap objects in an escaper
            return $this->factory->newInstance($raw);
        }
        
        // not a string, array, or object; leave it alone
        return $raw;
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
