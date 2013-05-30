<?php
namespace Aura\Html\Helper;

class AttribsTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $attribs = $this->helper;
        
        $values = [
            'foo' => 'bar',
            'nim' => null,
            'baz' => ['dib', 'zim', 'gir'],
            'required' => true,
            'optional' => false,
        ];
        
        $expect = 'foo="bar" baz="dib zim gir" required';
        $actual = $attribs($values);
        $this->assertSame($expect, $actual);
    }
    
    public function test__invokeNoAttribs()
    {
        $attribs = $this->helper;
        
        $values = [];
        
        $expect = '';
        $actual = $attribs($values);
        $this->assertSame($expect, $actual);
    }
}
