<?php
namespace Aura\Html;

class HelperFactory
{
    public $escaper;
    
    public $registry;
    
    public function __construct(
        Escaper $escaper,
        array $registry = [])
    {
        $this->escaper = $escaper;
        $this->registry = $registry;
    }
    
    public function newInstance($name)
    {
        if (empty($this->registry[$name])) {
            throw new Exception\HelperNotFound($name);
        }
        
        $func = $this->registry[$name];
        $helper = $func();
        $helper->setEscaper($this->escaper);
        return $helper;
    }
}
