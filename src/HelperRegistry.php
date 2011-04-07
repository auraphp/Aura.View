<?php
namespace aura\view;
use aura\di\ForgeInterface;

/**
 * 
 * Combination factory and registry for view helpers.
 * 
 * Note that helpers are stored by class, not by name; this means that
 * calling the same class by different names will still result in using
 * the same class. This is so that different packages can use different
 * names for the same class across their templates.
 * 
 */
class HelperRegistry
{
    protected $forge;
    protected $store;
    
    public function __construct(ForgeInterface $forge)
    {
        $this->forge = $forge;
    }
    
    public function getInstance($class)
    {
        if (! isset($this->store[$class])) {
            $this->store[$class] = $this->newInstance($class);
        }
        return $this->store[$class];
    }
    
    public function newInstance($class)
    {
        return $this->forge->newInstance($class);
    }
}
