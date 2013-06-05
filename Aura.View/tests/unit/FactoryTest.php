<?php
namespace Aura\View;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->finder = new Finder;
        $this->finder->setClosure('closure_tpl', function () {
            echo 'Finder closure template';
        });
        
        $this->finder->setPrefixes(['Aura\View']);
        
        $this->factory = new Factory;
        $this->factory->setFinder($this->finder);
        $this->helper = new MockHelper;
        $this->data = (object) [];
    }
    
    public function testGetFinder()
    {
        $this->assertSame($this->finder, $this->factory->getFinder());
    }
    
    public function testNewInstance_closure()
    {
        $closure = function () {
            echo 'Inline closure template';
        };
        
        $template = $this->factory->newInstance($closure, $this->helper, $this->data);
        ob_start();
        $template();
        $actual = ob_get_clean();
        $this->assertSame('Inline closure template', $actual);
    }
    
    public function testNewInstance_templateNotFound()
    {
        $template = $this->factory->newInstance('no_such_template', $this->helper, $this->data);
        $this->assertFalse($template);
    }
    
    public function testNewInstance_finderClosure()
    {
        $template = $this->factory->newInstance('closure_tpl', $this->helper, $this->data);
        ob_start();
        $template();
        $actual = ob_get_clean();
        $this->assertSame('Finder closure template', $actual);
    }
    
    public function testNewInstance_finderClass()
    {
        $template = $this->factory->newInstance('MockTemplate', $this->helper, $this->data);
        $template->noun = 'World';
        ob_start();
        $template();
        $actual = ob_get_clean();
        $this->assertSame('Hello World!', $actual);
    }
}
