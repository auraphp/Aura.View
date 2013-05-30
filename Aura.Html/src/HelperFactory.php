<?php
namespace Aura\Html;

class HelperFactory
{
    public $escape;
    
    public $registry;
    
    public function __construct(
        Escape $escape,
        array $registry = [])
    {
        $this->escape = $escape;
        $this->registry = $registry;
    }
    
    public function newInstance($name)
    {
        if (empty($this->registry[$name])) {
            throw new Exception\HelperNotFound($name);
        }
        
        $func = $this->registry[$name];
        $helper = $func();
        $helper->setEscape($this->escape);
        return $helper;
    }
}
