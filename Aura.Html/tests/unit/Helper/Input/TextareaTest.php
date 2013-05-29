<?php
namespace Aura\Html\Helper\Input;

class TextareaTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $textarea = new Textarea;
        
        $actual = $textarea([
            'name' => 'field',
            'value' => "the quick brown fox",
        ]);
        
        $expect = '<textarea name="field">the quick brown fox</textarea>';
        
        $this->assertSame($expect, $actual);
    }
}
