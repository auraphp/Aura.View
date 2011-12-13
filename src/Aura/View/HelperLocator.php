<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View;

/**
 * 
 * The Aura View Helper Locator
 * 
 * @package Aura.View
 * 
 */
class HelperLocator
{
    /**
     * 
     * A registry to store values
     * 
     * @var array
     * 
     */
    protected $registry;
    
    /**
     * 
     * Constructor.
     * 
     * @param array $registry This will allow developers to use the DI system 
     * of their choice to set up the helper locator.
     * 
     */
    public function __construct(array $registry = array())
    {
        foreach ($registry as $name => $spec) {
            $this->set($name, $spec);
        }
    }
    
    /*
     * Set the registry with the name
     * 
     * @param string $name
     * 
     * @param string $spec
     * 
     * 
     */
    public function set($name, $spec)
    {
        $this->registry[$name] = $spec;
    }
    
    /*
     * Gets from the registry 
     * 
     * @param string $name
     * 
     * @return string
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
