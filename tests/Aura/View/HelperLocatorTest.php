<?php
namespace Aura\View;
class HelperLocatorTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function test__constructAndGet()
    {
        $helper_locator = new HelperLocator([
            'mockHelper' => function() {
                return new \Aura\View\Helper\MockHelper;
            },
        ]);
        
        $expect = 'Aura\View\Helper\MockHelper';
        $actual = $helper_locator->get('mockHelper');
        $this->assertInstanceOf($expect, $actual);
    }

    public function testSetAndGet()
    {
        $helper_locator = new HelperLocator;
        $helper_locator->set('mockHelper', function () {
            return new \Aura\View\Helper\MockHelper;
        });
        
        $expect = 'Aura\View\Helper\MockHelper';
        $actual = $helper_locator->get('mockHelper');
        $this->assertInstanceOf($expect, $actual);
    }
    
    public function testGet_noSuchHelper()
    {
        $helper_locator = new HelperLocator;
        $this->setExpectedException('Aura\View\Exception\HelperNotMapped');
        $helper_locator->get('noSuchHelper');
    }
}
