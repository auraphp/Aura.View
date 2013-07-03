<?php
namespace Aura\View;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    protected $template;
    
    protected $finder;
    
    protected $helper;
    
    protected function setUp()
    {
        $this->finder = new Finder;
        $this->finder->setName('full', function () {
            echo 'Hello Full Template!';
        });
        $this->finder->setName('partial', function () {
            echo 'Hello Partial ' . $this->noun . '!';
        });
        
        $this->helper = new MockHelper;
        
        $this->template = new Template;
        $this->template->setFinder($this->finder);
        $this->template->setHelper($this->helper);
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
        $this->assertSame($this->finder, $this->template->getFinder());
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

    public function testSetHelper_invalidHelper()
    {
        $this->setExpectedException('Aura\View\Exception\InvalidHelper');
        $this->template->setHelper('not a helper');
    }
    
    public function testRender()
    {
        $actual = $this->template->render('full');
        $this->assertSame('Hello Full Template!', $actual);
        
        // missing template
        $this->setExpectedException('Aura\View\Exception\TemplateNotFound');
        $this->template->render('no-such-template');
    }
    
    public function testPartial()
    {
        $actual = $this->template->partial('partial', ['noun' => 'World']);
        $this->assertSame('Hello Partial World!', $actual);
    }
    
    public function testRequire()
    {
        // note that this is not a mock
        $finder = $this->template->getFinder();
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'foo_template.php';
        $finder->setName('foo', $file);
        $actual = $this->template->render('foo');
        $expect = 'Hello Foo!';
        $this->assertSame($expect, $actual);
    }
}
