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
namespace Aura\View;

/**
 * 
 * A ServiceLocator implementation for loading and retaining helper objects.
 * 
 */
class HelperLocator
{
    /**
     * 
     * A registry to retain helper objects.
     * 
     * @var array
     * 
     */
    protected $registry;
    
    /**
     * 
     * Constructor.
     * 
     * @param array $registry An array of key-value pairs where the key is the
     * helper name (doubles as a method name) and the value is the helper
     * object. The value may also be a closure that returns a helper object.
     * 
     */
    public function __construct(array $registry = array())
    {
        foreach ($registry as $name => $spec) {
            $this->set($name, $spec);
        }
    }
    
    /**
     * 
     * Sets a helper into the registry by name.
     * 
     * @param string $name The helper name; this doubles as a method name
     * when called from a template.
     * 
     * @param string $spec The helper specification, typically a closure that
     * builds and returns a helper object.
     * 
     * @return void
     * 
     */
    public function set($name, $spec)
    {
        $this->registry[$name] = $spec;
    }
    
    /**
     * 
     * Gets a helper from the registry by name.
     * 
     * @param string $name The helper to retrieve.
     * 
     * @return AbstractHelper A helper object.
     * 
     */
    public function get($name)
    {
        if (! isset($this->registry[$name])) {
            throw new Exception\HelperNotMapped($name);
        }
        
        if ($this->registry[$name] instanceof \Closure) {
            $func = $this->registry[$name];
            $this->registry[$name] = $func();
        }
        
        return $this->registry[$name];
    }
}
