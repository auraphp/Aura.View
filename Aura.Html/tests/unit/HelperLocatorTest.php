<?php
namespace Aura\Html;

class HelperLocatorTest extends \PHPUnit_Framework_TestCase
{
    protected $helper_locator;
    
    protected function setUp()
    {
        $escape = new Escape(
            new Escape\AttrStrategy,
            new Escape\CssStrategy,
            new Escape\HtmlStrategy,
            new Escape\JsStrategy
        );
        
        $registry = [
            'mockHelper' => function () {
                return new Helper\MockHelper;
            },
        ];
        
        $helper_factory = new HelperFactory($escape, $registry);
        
        $this->helper_locator = new HelperLocator($helper_factory);
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function test()
    {
        $expect = 'Aura\Html\Helper\MockHelper';
        $actual = $this->helper_locator->get('mockHelper');
        $this->assertInstanceOf($expect, $actual);

        $expect = 'Hello World';
        $actual = $this->helper_locator->mockHelper('World');
        $this->assertSame($expect, $actual);
        
        $this->setExpectedException('Aura\Html\Exception\HelperNotFound');
        $this->helper_locator->get('noSuchHelper');
    }
}
