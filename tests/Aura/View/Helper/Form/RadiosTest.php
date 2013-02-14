<?php
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelperTest;

class RadiosTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $attribs = ['type' => '', 'name' => 'field', 'value' => ''];
        $options = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ];
        
        $radios = new Radios(new Input\Checked);
        
        $actual = $radios($attribs, $options, 'baz');
        $expect = '<label><input type="radio" name="field" value="foo" /> bar</label>' . PHP_EOL
                . '<label><input type="radio" name="field" value="baz" checked="checked" /> dib</label>' . PHP_EOL
                . '<label><input type="radio" name="field" value="zim" /> gir</label>' . PHP_EOL;
                
        $this->assertSame($expect, $actual);
    }
}
