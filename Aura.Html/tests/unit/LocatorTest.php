<?php
namespace Aura\Html;

use Aura\Html\Helper\MockHelper;

class LocatorTest extends \PHPUnit_Framework_TestCase
{
    protected $locator;
    
    protected function setUp()
    {
        $this->locator = new Locator([
            'mockHelper' => function () {
                return new MockHelper;
            },
        ]);
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function test()
    {
        $expect = 'Aura\Html\Helper\MockHelper';
        $actual = $this->locator->get('mockHelper');
        $this->assertInstanceOf($expect, $actual);

        $expect = 'Hello World';
        $actual = $this->locator->mockHelper('World');
        $this->assertSame($expect, $actual);
        
        $this->setExpectedException('Aura\Html\Exception\HelperNotFound');
        $this->locator->get('noSuchHelper');
    }
}
