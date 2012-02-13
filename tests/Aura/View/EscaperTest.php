<?php
namespace Aura\View;
class EscaperTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    public function testIteration()
    {
        $data = ['foo', 'bar', 'baz'];
        $object = new \ArrayObject($data);
        $e = new Escaper($object);
        foreach ($e as $key => $val) {
            echo $val . PHP_EOL;
            $this->assertSame($data[$key], $val);
        }
        echo "Done.";
    }
}
