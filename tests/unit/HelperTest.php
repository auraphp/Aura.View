<?php
namespace Aura\View;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    protected function setUp()
    {
        $this->helper = new Helper;
    }
    
    public function testSafeHtml()
    {
        $string = '\'apos\' "quote" & <angle>';
        $actual = $this->helper->safeHtml($string);
        $expect = '&#039;apos&#039; &quot;quote&quot; &amp; &lt;angle&gt;';
        $this->assertSame($expect, $actual);
    }
    
    public function testSafeAttr_string()
    {
        $string = '\'apos\' \"quote\" & <angle>';
        $actual = $this->helper->safeAttr($string);
        $expect = 'aposquoteangle';
        $this->assertSame($expect, $actual);
    }
    
    public function testSafeAttr_array()
    {
        $array = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => false,
            'qux' => true,
            'quux' => ['a', 'b', 'c', 'd']
        ];
        $actual = $this->helper->safeAttr($array);
        $expect = 'foo="bar" baz="dib" qux quux="a b c d"';
        $this->assertSame($expect, $actual);
    }
    
    public function testMagic()
    {
        $this->helper->set('hello', function ($noun) {
            return 'Hello ' . $this->safeHtml($noun) . '!';
        });
        
        $actual = $this->helper->hello('World');
        $expect = 'Hello World!';
        $this->assertSame($expect, $actual);
    }
}
