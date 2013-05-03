<?php
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelperTest;

class TextareaTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $textarea = new Textarea;
        
        $actual = $textarea(
            [
                'name' => 'field',
            ],
            "the quick brown fox"
        );
        
        $expect = '<textarea name="field">the quick brown fox</textarea>';
        
        $this->assertSame($expect, $actual);
    }
}
