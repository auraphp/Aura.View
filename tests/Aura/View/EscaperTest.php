<?php
namespace Aura\View\Escaper;
use Aura\View\EscaperFactory;
use Aura\View\MockModelIterator;

class EscaperTest extends \PHPUnit_Framework_TestCase
{
    protected $escaper_factory;
    
    protected function setUp()
    {
        parent::setUp();
        $this->escaper_factory = new EscaperFactory;
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    public function testIteratorAggregateAndArrayObject()
    {
        $data    = ['foo', 'bar', 'baz', 123, true, false, null];
        $escaper = $this->escaper_factory->newInstance($data);
        $i       = 0;
        foreach ($escaper as $key => $val) {
            $i++;
            $this->assertSame($data[$key], $val);
        }
        $this->assertSame($i, count($data));
    }
    
    public function testIterator()
    {
        $data    = ['foo', 'bar', 'baz', 123, true, false, null];
        $model   = new MockModelIterator($data);
        $escaper = $this->escaper_factory->newInstance($model);
        $i       = 0;
        foreach ($escaper as $key => $val) {
            $i++;
            $this->assertSame($data[$key], $val);
        }
        $this->assertSame($i, count($data));
    }
    
    public function test__getClass()
    {
        $data    = ['foo', 'bar', 'baz', 123, true, false, null];
        $model   = new MockModelIterator($data);
        $escaper = $this->escaper_factory->newInstance($model);
        $actual  = $escaper->__getClass();
        $expect  = 'Aura\View\MockModelIterator';
        $this->assertSame($expect, $actual);
    }
    
    public function test__call()
    {
        $data    = ['foo', 'bar', 'baz', 123, true, false, null];
        $model   = new MockModelIterator($data);
        $escaper = $this->escaper_factory->newInstance($model);
        $actual  = $escaper->getThroughCall();
        $expect  = 'Aura\View\MockModelIterator::getThroughCall';
        $this->assertSame($expect, $actual);
    }
    
    public function testEscapingStrings()
    {
        $data    = ['<foo>', '&bar', '"baz"'];
        $escaper = $this->escaper_factory->newInstance($data);
        $i       = 0;
        foreach ($escaper as $key => $val) {
            $i++;
            $expect = htmlspecialchars($data[$key], ENT_QUOTES, 'UTF-8');
            $this->assertSame($expect, $val);
        }
        $this->assertSame($i, count($data));
    }

    public function testEscapingNonStrings()
    {
        $data    = [123.456, 789, true, false, null];
        $escaper = $this->escaper_factory->newInstance($data);
        $i       = 0;
        foreach ($escaper as $key => $val) {
            $i++;
            $this->assertSame($data[$key], $val);
        }
        $this->assertSame($i, count($data));
    }
    
    public function test__raw()
    {
        $data    = ['<foo>', '&bar', '"baz"'];
        $escaper = $this->escaper_factory->newInstance($data);
        $i       = 0;
        foreach ($escaper->__raw() as $key => $val) {
            $i++;
            $this->assertSame($data[$key], $val);
        }
        $this->assertSame($i, count($data));
    }
}
