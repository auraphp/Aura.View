<?php
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelperTest;

class CheckboxesTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $attribs = ['type' => '', 'name' => 'field[]', 'value' => ''];
        $options = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ];

        $checkboxes = new Checkboxes(new Input\Checked);

        $actual = $checkboxes($attribs, $options, array('baz'));
        $expect = '<label><input type="checkbox" name="field[]" value="foo" /> bar</label>' . PHP_EOL
                . '<label><input type="checkbox" name="field[]" value="baz" checked="checked" /> dib</label>' . PHP_EOL
                . '<label><input type="checkbox" name="field[]" value="zim" /> gir</label>' . PHP_EOL;

        $this->assertSame($expect, $actual);
    }
}