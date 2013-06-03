<?php
namespace Aura\View;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->finder = new Finder;
        $this->finder->setClosure('closure_tpl', function () {
            echo 'Closure-based template';
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
    
    public function testNewInstance_templateNotFound()
    {
        $this->setExpectedException('Aura\View\Exception\TemplateNotFound');
        $this->factory->newInstance('no_such_template', $this->helper, $this->data);
    }
    
    public function testNewInstance_closure()
    {
        $template = $this->factory->newInstance('closure_tpl', $this->helper, $this->data);
        ob_start();
        $template();
        $actual = ob_get_clean();
        $this->assertSame('Closure-based template', $actual);
    }
    
    public function testNewInstance_class()
    {
        $template = $this->factory->newInstance('MockTemplate', $this->helper, $this->data);
        $template->noun = 'World';
        ob_start();
        $template();
        $actual = ob_get_clean();
        $this->assertSame('Hello World!', $actual);
    }
}
