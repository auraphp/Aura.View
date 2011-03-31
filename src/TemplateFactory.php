<?php
namespace aura\view;
use aura\di\Forge;

class TemplateFactory
{
    protected $forge;
    
    public function __construct(ForgeInterface $forge)
    {
        $this->forge = $forge;
    }
    
    public function newInstance()
    {
        return $this->forge->newInstance('aura\view\Template');
    }
}
