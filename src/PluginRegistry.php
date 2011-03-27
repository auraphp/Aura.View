<?php
namespace aura\view;
use aura\di\ForgeInterface;

/**
 * 
 * Combination factory and registry for view plugins.
 * 
 */
class PluginRegistry
{
    protected $forge;
    protected $map;
    
    public function __construct(ForgeInterface $forge, array $map = null)
    {
        $this->forge = $forge;
        $this->map = $map;
    }
    
    public function get($name)
    {
        if (! isset($this->map[$name])) {
            throw new Exception_PluginNotMapped($name);
        }
        
        if (is_string($this->map[$name])) {
            $class = $this->map[$name];
            $this->map[$name] = $this->forge->newInstance($class);
        }
        
        if ($this->map[$name] instanceof \Closure) {
            $func = $this->map[$name];
            $this->map[$name] = $func();
        }
        
        return $this->map[$name];
    }
}
