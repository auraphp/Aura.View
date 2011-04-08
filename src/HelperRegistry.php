<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace aura\view;
use aura\di\ForgeInterface;

/**
 * 
 * Combination factory and registry for view helpers.
 * 
 * @package aura.view
 * 
 */
class HelperRegistry
{
    /**
     * 
     * An object-creation forge.
     * 
     * @var aura\di\ForgeInterface
     * 
     */
    protected $forge;
    
    /**
     * 
     * A map of method (alias) names to helper classes.
     * 
     * @var array
     * 
     */
    protected $map = array();
    
    /**
     * 
     * Stores the registered helpers classes by their class name.
     * 
     * @var array
     * 
     */
    protected $store = array();
    
    /**
     * 
     * Constructor.
     * 
     * @param aura\di\ForgeInterface $forge An object-creation forge.
     * 
     */
    public function __construct(ForgeInterface $forge, array $map = array())
    {
        $this->forge = $forge;
        $this->map = $map;
    }
    
    /**
     * 
     * Gets a shared instance of a helper from the registry, creating and
     * registering it as needed.
     * 
     * @param string $class The helper class to register and retrieve.
     * 
     * @return mixed
     * 
     */
    public function getInstance($name)
    {
        if (! isset($this->store[$name])) {
            $this->store[$name] = $this->newInstance($name);
        }
        return $this->store[$name];
    }
    
    /**
     * 
     * Creates a new instance of a helper, but does not retain it in the 
     * registry for shared use.
     * 
     * @param string $class The helper class to instantiate.
     * 
     * @return mixed
     * 
     */
    public function newInstance($name)
    {
        if (! isset($this->map[$name])) {
            throw new Exception_HelperNotMapped($name);
        }
        $class = $this->map[$name];
        return $this->forge->newInstance($class);
    }
}
