<?php
namespace Aura\Html\Helper\Input;

use Aura\Html\Helper\AbstractHelperTest;

class RadioTest extends AbstractHelperTest
{
    public function test()
    {
        $attribs = ['type' => '', 'name' => 'field', 'value' => ''];
        
        $options = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ];
        
        $radio = $this->helper;
        
        $actual = $radio([
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
