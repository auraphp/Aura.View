<?php
namespace Aura\View;
class HelperLocator
{
    protected $registry;
    
    public function __construct(array $registry = array())
    {
        foreach ($registry as $name => $spec) {
            $this->set($name, $spec);
        }
    }
    
    public function set($name, $spec)
    {
        $this->registry[$name] = $spec;
    }
    
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
