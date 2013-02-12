<?php
namespace Aura\View\Helper;

class AttrTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $attr = new Attr;
        
        $values = $this->escape([
            'foo' => 'bar',
            'nim' => '',
            'baz' => ['dib', 'zim', 'gir'],
            'required' => true,
            'optional' => false,
        ]);
        
        $expect = 'foo="bar" baz="dib zim gir" required';
        $actual = $attr($values);
        $this->assertSame($expect, $actual);
    }
    
    public function test__invokeNoAttr()
    {
        $attr = new Attr;
        $values = $this->escape([]);
        $expect = '';
        $actual = $attr($values);
        $this->assertSame($expect, $actual);
    }
}
