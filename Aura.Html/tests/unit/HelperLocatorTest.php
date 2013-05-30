<?php
namespace Aura\Html;

class HelperLocatorTest extends \PHPUnit_Framework_TestCase
{
    protected $helper_locator;
    
    protected function setUp()
    {
        $this->helper_locator = new HelperLocator(new HelperFactory(
            new Escaper,
            [
                'mockHelper' => function () {
                    return new Helper\MockHelper;
                },
            ]
        ));
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
