<?php
namespace Aura\View\Helper;

class RadiosTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $attr = ['type' => '', 'name' => 'field', 'value' => ''];
        $opts = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ];
        
        $radios = new Radios(new Input);
        
        $actual = $radios(
            $this->escape($attr),
            $this->escape($opts),
            $this->escape('baz')
        );
        
        $expect = '<label><input type="radio" name="field" value="foo" /> bar</label>' . PHP_EOL
                . '<label><input type="radio" name="field" value="baz" checked="checked" /> dib</label>' . PHP_EOL
                . '<label><input type="radio" name="field" value="zim" /> gir</label>' . PHP_EOL;
                
        $this->assertSame($expect, $actual);
    }
}
