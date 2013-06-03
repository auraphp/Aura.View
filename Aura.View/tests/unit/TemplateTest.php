<?php
namespace Aura\View;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    protected $template;
    
    protected $factory;
    
    protected $helper;
    
    protected function setUp()
    {
        $this->finder = new Finder;
        $this->finder->setClosure('another_template', function () {
            echo 'Hello Another Template!';
        });
        $this->finder->setClosure('partial_template', function () {
            echo 'Hello Partial ' . $this->partial_noun . '!';
        });
        
        $this->factory = new Factory;
        $this->factory->setFinder($this->finder);
        
        $this->helper = new MockHelper;
        
        $this->template = new MockTemplate(
            $this->factory,
            $this->helper,
            []
        );
    }
    
    public function testMagicMethods()
    {
        // __isset()
        $this->assertFalse(isset($this->template->foo));
        
        // __set()/__isset()
        $this->template->foo = 'bar';
        $this->assertTrue(isset($this->template->foo));
        
        // __get()
        $this->assertSame('bar', $this->template->foo);
        
        // __unset()
        unset($this->template->foo);
        $this->assertFalse(isset($this->template->foo));
        
        // __call()
        $actual = $this->template->helloHelper();
        $this->assertSame('Hello Helper', $actual);
    }
    
    public function testGetters()
    {
        $this->assertSame($this->factory, $this->template->getFactory());
        $this->assertSame($this->helper, $this->template->getHelper());
    }
    
    public function testSetAndGetData()
    {
        $data = [
            'foo' => 'bar'
        ];
        $this->template->setData($data);
        $this->assertSame('bar', $this->template->foo);
        
        $actual = (array) $this->template->getData();
        $this->assertSame($data, $actual);
    }

    public function testInvoke()
    {
        $template = $this->template;
        $template->noun = 'World';
        ob_start();
        $template();
        $actual = ob_get_clean();
        $expect = 'Hello World!';
    }
    
    public function testSetHelper_invalidHelper()
    {
        $this->setExpectedException('Aura\View\Exception\InvalidHelper');
        $this->template->setHelper('not a helper');
    }
    
    public function testRender()
    {
        // main data
        $actual = $this->template->render('another_template');
        $this->assertSame('Hello Another Template!', $actual);
        
        // separated data
        $actual = $this->template->render('partial_template', ['partial_noun' => 'World']);
        $this->assertSame('Hello Partial World!', $actual);
        
        // missing template
        $this->setExpectedException('Aura\View\Exception\TemplateNotFound');
        $this->template->render('no-such-template');
    }
}
