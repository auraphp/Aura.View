<?php
namespace Aura\View\Escaper;
use Aura\View\EscaperFactory;
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
    
    public function testIteration()
    {
        $data = ['foo', 'bar', 'baz'];
        $i = 0;
        $e = $this->escaper_factory->newInstance($data);
        foreach ($e as $key => $val) {
            $i++;
            $this->assertSame($data[$key], $val);
        }
        $this->assertSame($i, count($data));
    }
}
