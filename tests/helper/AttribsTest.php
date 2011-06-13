<?php
namespace Aura\View\helper;

class AttribsTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $attribs = new Attribs;
        
        $values = array(
            'foo' => 'bar',
            'nim' => '',
            'baz' => array('dib', 'zim', 'gir'),
            'required' => true,
            'optional' => false,
        );
        
        $expect = 'foo="bar" baz="dib zim gir" required';
        $actual = $attribs($values);
        $this->assertSame($expect, $actual);
    }
    
    public function test__invokeNoAttribs()
    {
        $attribs = new Attribs;
        $values = array();
        $expect = '';
        $actual = $attribs($values);
        $this->assertSame($expect, $actual);
    }
}
