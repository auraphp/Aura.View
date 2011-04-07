<?php
namespace aura\view\helper;

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
        
        $expect = ' foo="bar" baz="dib zim gir" required';
        $actual = $attribs($values);
        $this->assertSame($expect, $actual);
    }
}
