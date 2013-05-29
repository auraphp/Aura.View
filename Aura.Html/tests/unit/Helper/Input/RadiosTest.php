<?php
namespace Aura\Html\Helper\Input;

class RadiosTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $attribs = ['type' => '', 'name' => 'field', 'value' => ''];
        $options = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ];
        
        $radios = new Radios(new Radio);
        
        $actual = $radios([
            'name' => 'field',
            'value' => 'baz',
            'attribs' => $attribs,
            'options' => $options,
        ]);
        
        $expect = '<label><input type="radio" name="field" value="foo" /> bar</label>' . PHP_EOL
                . '<label><input type="radio" name="field" value="baz" checked /> dib</label>' . PHP_EOL
                . '<label><input type="radio" name="field" value="zim" /> gir</label>' . PHP_EOL;
                
        $this->assertSame($expect, $actual);
    }
}
