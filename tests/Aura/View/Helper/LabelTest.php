<?php
namespace Aura\View\Helper;

class LabelTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $label = new Label;
        
        $actual = $label('Label Text', [
                'for' => 'foo',
        ]);
        
        $expect = '<label for="foo">Label Text</label>';
        
        $this->assertSame($expect, $actual);
    }
}
